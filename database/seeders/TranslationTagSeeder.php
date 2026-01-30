<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TranslationTagSeeder extends Seeder
{
    public function run(): void
    {
        $translations = DB::table('translations')->pluck('id')->toArray();
        $tags = DB::table('tags')->pluck('id')->toArray();

        if (empty($translations) || empty($tags)) {
            return;
        }

        $rows = [];
        $now = now();

        foreach (array_slice($translations, 0, 20_000) as $translationId) {
            $rows[] = [
                'translation_id' => $translationId,
                'tag_id' => $tags[array_rand($tags)],
            ];
        }

        DB::table('tag_translations')->insertOrIgnore($rows);

        //for UI purpose
        $translation = [
            [
                "key" => "app.title",
                "en" => "My Application",
                "tg" => "Aking Aplikasyon",
                "de" => "Meine Anwendung",
                "es" => "Mi Aplicación"
            ],
            [
                "key" => "app.description",
                "en" => "This is a sample application.",
                "tg" => "Ito ay isang halimbawa ng aplikasyon.",
                "de" => "Dies ist eine Beispielanwendung.",
                "es" => "Esta es una aplicación de ejemplo."
            ],
            [
                "key" => "button.submit",
                "en" => "Submit",
                "tg" => "Isumite",
                "de" => "Einreichen",
                "es" => "Enviar"
            ],
            [
                "key" => "button.cancel",
                "en" => "Cancel",
                "tg" => "Kanselahin",
                "de" => "Abbrechen",
                "es" => "Cancelar"
            ],
            [
                "key" => "message.welcome",
                "en" => "Welcome to our application!",
                "tg" => "Maligayang pagdating sa aming aplikasyon!",
                "de" => "Willkommen in unserer Anwendung!",
                "es" => "¡Bienvenido a nuestra aplicación!"
            ],
            [
                "key" => "button.language",
                "en" => "Language",
                "tg" => "Wika",
                "de" => "Sprache",
                "es" => "Idioma"
            ]
        ];

        
    }
}
