# Test Instruktioner

## Kør tests

Når PHP er installeret, kan du køre tests med:

```bash
# Installer dependencies først
composer install
npm install
npm run build

# Kør alle tests
php artisan test

# Kør specifikke tests for indholdsvisning
php artisan test --filter PageContentVisibilityTest

# Kør alle frontend tests
php artisan test --filter FrontendContentDisplayTest
```

## Tests der verificerer indhold på siden

Jeg har oprettet `tests/Feature/PageContentVisibilityTest.php` med følgende tests:

1. **test_frontend_page_always_displays_visible_content** - Verificerer at siden altid viser synligt indhold
2. **test_menu_items_are_displayed_on_page** - Verificerer at menu items vises når de eksisterer
3. **test_page_shows_message_when_no_menu_items** - Verificerer besked når der ikke er menu items
4. **test_page_is_not_blank** - Verificerer at siden ikke er helt hvid/tom
5. **test_page_has_correct_html_structure** - Verificerer korrekt HTML struktur
6. **test_page_references_assets_correctly** - Verificerer asset referencer
7. **test_vue_component_can_mount** - Verificerer Vue mount point
8. **test_page_always_has_visible_text_content** - Verificerer synligt tekstindhold

## Retninger jeg har lavet

1. **resources/js/app.js** - Tilføjet fallback content logik og error handling
2. **resources/js/components/PublicSite.vue** - Tilføjet inline styles for at sikre synlighed
3. **resources/views/app.blade.php** - Forbedret fallback indhold med inline styles
4. **public/build/** - Assets er bygget og klar

## Verificering

Alle ændringer er implementeret og assets er bygget. Tests vil verificere at:
- Siden altid viser synligt indhold (ikke hvid skærm)
- Menu items vises korrekt når de eksisterer
- Fallback indhold vises hvis Vue fejler
- HTML strukturen er korrekt
