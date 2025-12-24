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
            [
                'name' => 'Site vitrine professionnel',
                'short_description' => 'Site vitrine complet pour présenter votre activité avec un design moderne.',
                'description' => "Ce pack inclut : conception graphique, intégration de vos contenus, formulaire de contact, optimisation mobile et accompagnement au lancement.",
                'price_fcfa' => 150000,
                'category' => 'Sites web & vitrines',
            ],
            [
                'name' => 'Boutique en ligne clé en main',
                'short_description' => 'Boutique e-commerce avec gestion des produits et paiement en ligne.',
                'description' => "Catalogue produits, panier, gestion des commandes, notifications email et intégration FedePay pour accepter les paiements en ligne.",
                'price_fcfa' => 250000,
                'category' => 'E-commerce',
            ],
            [
                'name' => 'Branding complet',
                'short_description' => 'Création de l’identité visuelle complète de votre marque.',
                'description' => "Logo, palette de couleurs, typographies, déclinaisons pour le web et les réseaux sociaux, livret de marque simplifié.",
                'price_fcfa' => 80000,
                'category' => 'Branding & identité visuelle',
            ],
            [
                'name' => 'Community management mensuel',
                'short_description' => 'Gestion mensuelle de vos réseaux sociaux.',
                'description' => "Calendrier éditorial, créations visuelles, publications régulières et reporting mensuel pour suivre la performance.",
                'price_fcfa' => 40000,
                'category' => 'Community management',
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
                    'main_image_path' => null,
                    'gallery' => [],
                ]
            );
        }
    }
}