# Event Manager

Wtyczka do zarządzania wydarzeniami w WordPress, oferująca niestandardowy typ postu (CPT), dedykowaną taksonomię oraz mechanizm rejestracji uczestników za pomocą AJAX.

---

## Instalacja

### Wymagania
* **Wersja WordPress:** Minimum 6.4
* **Wersja PHP:** Minimum 8.1
* **Wymagane Wtyczki:**
    * **Advanced Custom Fields (ACF)** - wtyczka wykorzystuje pola ACF do przechowywania kluczowych danych wydarzeń (data, limit, opis).

### Kroki instalacji
1.  **Pobierz kod:** Sklonuj to repozytorium lub pobierz je jako plik ZIP.
2.  **Prześlij do WP:** W panelu administracyjnym WordPress przejdź do **Wtyczki** > **Dodaj nową** > **Prześlij wtyczkę** i wskaż pobrany plik ZIP.
    * *Alternatywnie:* Rozpakuj plik ZIP i wgraj folder `event-manager` do katalogu `wp-content/plugins/` na serwerze.
3.  **Aktywacja:** Przejdź do **Wtyczki** > **Zainstalowane wtyczki** i aktywuj **Event Manager**.
4.  **Odświeżanie permalinków:** Po aktywacji **obowiązkowo** przejdź do **Ustawienia** > **Bezpośrednie odnośniki** i kliknij **Zapisz zmiany**, aby upewnić się, że strony dla nowego CPT `event` działają poprawnie.

---

## Funkcjonalność

Głównym zadaniem wtyczki jest stworzenie pełnego ekosystemu do zarządzania wydarzeniami:

* **Custom Post Type (CPT):** Dodaje nowy typ postu o nazwie **Wydarzenia** (`event`).
* **Dedykowana Taksonomia:** Dodaje taksonomię **Miejscowości** (`city`) do kategoryzowania wydarzeń.
* **Pola ACF:** Do CPT `event` dodawane są pola ACF: `em_field_date` (Data i godzina), `em_participant_limit` (Limit uczestników) oraz `em_description` (Opis/szczegóły).
* **Rejestracja AJAX:** Umożliwia asynchroniczną rejestrację uczestników na stronie pojedynczego wydarzenia (`single-event.php`).
* **Zapis Danych:** Dane zarejestrowanych uczestników są zapisywane jako metadane postu wydarzenia pod kluczem `event_registrations`.

---

## AJAX Endpoints

Wtyczka wykorzystuje jeden endpoint do obsługi rejestracji na wydarzenia.

### Opis endpointu `/wp-admin/admin-ajax.php?action=em_register_event`

* **Akcja:** `em_register_event` (obsługiwana zarówno dla zalogowanych, jak i niezalogowanych użytkowników).
* **Plik obsługujący:** `includes/ajax-handlers.php`
* **Działanie:** Waliduje `nonce`, sprawdza, czy pole `event_id`, `name` i `email` są uzupełnione, weryfikuje limit miejsc (`em_participant_limit`) oraz sprawdza, czy dany e-mail nie został już zarejestrowany. Po pomyślnej weryfikacji, zapisuje dane uczestnika do post meta.

### Parametry (POST)

| Nazwa Parametru | Wymagany | Typ Danych | Opis |
| :--- | :--- | :--- | :--- |
| `action` | TAK | string | Stała wartość: `em_register_event` |
| `nonce` | TAK | string | Nonce zabezpieczający akcję. (Generowany przez `em_ajax_object.nonce` w JS). |
| `event_id` | TAK | int | ID wydarzenia (postu) z formularza. |
| `name` | TAK | string | Imię i nazwisko rejestrującej się osoby. |
| `email` | TAK | email | Adres e-mail uczestnika. |

### Przykładowy Response (JSON)

#### Sukces
```json
{
    "success": true,
    "data": {
        "message": "Dziękujemy za rejestrację!",
        "count": 5 // Aktualna liczba zarejestrowanych
    }
}
#### Błąd
{
    "success": false,
    "data": {
        "message": "Przykro nam, brak wolnych miejsc na to wydarzenie."
    }
}
