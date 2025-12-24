<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // 1) Service web commandable (site vitrine)
            [
                'name' => 'Site vitrine professionnel',
                'short_description' => 'Site vitrine complet pour présenter votre activité avec un design moderne.',
                'description' => "Ce pack inclut : conception graphique, intégration de vos contenus, formulaire de contact, optimisation mobile et accompagnement au lancement.",
                'price_fcfa' => 150000,
                'category' => 'Sites web & vitrines',
                'type' => 'service',
                'download_url' => null,
                'external_url' => null,
            ],

            // 2) Produit digital : livre de formation à acheter et télécharger
            [
                'name' => 'Livre de formation SWBS – Lancer son business en ligne',
                'short_description' => 'Un guide complet au format PDF pour structurer et lancer votre présence en ligne.',
                'description' => "Ce livre de formation couvre les bases du marketing digital, de la création de site, du choix des offres et de la mise en place d’un tunnel de vente simple. Format PDF téléchargeable après achat.",
                'price_fcfa' => 12000,
                'category' => 'Formations & ebooks',
                'type' => 'digital',
                'download_url' => 'https://swbs.site/downloads/livre-formation-business-en-ligne.pdf',
                'external_url' => null,
            ],

            // 3) Produit digital : template / plugin / thème à télécharger
            [
                'name' => 'Pack templates SWBS pour WordPress',
                'short_description' => 'Un pack de modèles de pages et de sections optimisées pour WordPress.',
                'description' => "Inclut plusieurs modèles de pages d’accueil, pages services, pages de contact et sections de témoignages. Fichiers téléchargeables après confirmation de la commande.",
                'price_fcfa' => 25000,
                'category' => 'Templates & plugins',
                'type' => 'digital',
                'download_url' => 'https://swbs.site/downloads/pack-templates-wordpress-swbs.zip',
                'external_url' => null,
            ],

            // 4) Produit d’affiliation : exemple de robe femme, redirection vers un autre site
            [
                'name' => 'Robe élégante – Partenaire SWBS',
                'short_description' => 'Produit d’affiliation : robe élégante vendue via un partenaire.',
                'description' => "Lorsque le client clique sur « Acheter », il est redirigé vers la boutique du partenaire pour finaliser la commande. Vous voyez dans l’admin qu’un clic / une intention d’achat a été généré depuis SWBS.",
                'price_fcfa' => 0,
                'category' => 'Affiliation & partenaires',
                'type' => 'affiliate',
                'download_url' => null,
                'external_url' => 'https://exemple-partenaire.com/robe-elegante?utm_source=swbs',
            ],
        ];

        foreach ($products as $data) {
            $category = Category::where('slug', Str::slug($data['category']))->first();

            Product::updateOrCreate(
                ['slug' => Str::slug($data['name'])],
                [
                    'name' => $data['name'],
                    'short_description' => $data['short_description'],
                    'description' => $data['description'],
                    'price_fcfa' => $data['price_fcfa'],
                    'is_active' => true,
                    'stock' => 999,
                    'category_id' => $category?->id,
                    'type' => $data['type'] ?? 'standard',
                    'download_url' => $data['download_url'] ?? null,
                    'external_url' => $data['external_url'] ?? null,
                    'main_image_path' => null,
                    'gallery' => [],
                ]
            );
        }
    }
}