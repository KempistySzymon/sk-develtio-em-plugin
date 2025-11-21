<?php

if(!defined('ABSPATH')){
    exit;
}

add_action('wp_ajax_em_register_event', 'em_handle_event_register'); // Dla zalogowanych
add_action( 'wp_ajax_nopriv_em_register_event', 'em_handle_event_register'); // Dla niezalogowanych

function em_handle_event_register(){ //To przekopiowałem na chama

    if ( ! check_ajax_referer( 'em_registration_nonce', 'nonce', false ) ) {
        wp_send_json_error( [ 'message' => 'Nieprawidłowe żądanie bezpieczeństwa.' ] );
    }

    $event_id = isset( $_POST['event_id'] ) ? intval( $_POST['event_id'] ) : 0;
    $name     = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
    $email    = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';

    if ( ! $event_id || empty( $name ) || ! is_email( $email ) ) {
        wp_send_json_error( [ 'message' => 'Proszę uzupełnić imię oraz adres e-mail.' ] );
    }

    // Sprawdzanie limitu
    $limit = get_field( 'em_participant_limit', $event_id );

    // Pobieranie obecnej listych zapisanych
    $current_registrations = get_post_meta( $event_id, 'event_registrations', true );
    if ( ! is_array( $current_registrations ) ) {
        $current_registrations = [];
    }

    if ( $limit && count( $current_registrations ) >= (int)$limit ) { 
        wp_send_json_error( [ 'message' => 'Przykro nam, brak wolnych miejsc na to wydarzenie.' ] );
    }

    // Sprawdzamy czy mail, jest już na liście
    foreach ( $current_registrations as $participant ) {
        if ( isset($participant['email']) && $participant['email'] === $email ) {
            wp_send_json_error( [ 'message' => 'Ten adres email jest już zapisany na to wydarzenie.' ] );
        }
    }


    // Nowy uczestnik
    $new_participant = [
        'name'          => $name,
        'email'         => $email,
        'registered_at' => date( 'Y-m-d H:i' ),
        'user_ip'       => $_SERVER['REMOTE_ADDR']
    ];

    $current_registrations[] = $new_participant;

    // Zapis do bazy
    $updated = update_post_meta( $event_id, 'event_registrations', $current_registrations );

    if ( $updated ) {
        wp_send_json_success( [ 
            'message' => 'Dziękujemy za rejestrację!',
            'count'   => count( $current_registrations )
        ] );
    } else {
        wp_send_json_error( [ 'message' => 'Wystąpił błąd podczas zapisu do bazy.' ] );
    }

}