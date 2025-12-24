<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => 'Création de sites web professionnels',
                'short_description' => 'Des sites vitrines modernes et optimisés pour présenter votre activité.',
                'description' => "SWBS conçoit des sites vitrine élégants, rapides et responsives, pensés pour convertir vos visiteurs en clients. Nous intégrons vos contenus, mettons en avant vos services et optimisons l'expérience utilisateur pour refléter une image professionnelle et crédible.",
                'base_price_fcfa' => 150000,
            ],
            [
                'title' => 'Conception d’applications web',
                'short_description' => 'Applications web sur mesure pour digitaliser vos processus métiers.',
                'description' => "Nous développons des applications web métiers adaptées à vos besoins spécifiques : gestion interne, extranet client, plateforme de réservation, CRM, intranet, etc. L'objectif : automatiser vos opérations et centraliser vos données.",
                'base_price_fcfa' => null,
            ],
            [
                'title' => 'Identité visuelle & branding',
                'short_description' => 'Logo, charte graphique et univers de marque cohérent.',
                'description' => "SWBS vous accompagne dans la création d'une identité visuelle forte : logo professionnel, palette de couleurs, typographie, supports digitaux et print. Votre marque gagne en cohérence et en impact auprès de votre audience.",
                'base_price_fcfa' => 80000,
            ],
            [
                'title' => 'Marketing digital',
                'short_description' => 'Stratégies digitales pour attirer et convertir vos clients.',
                'description' => "Nous construisons et déployons des stratégies marketing orientées résultats : tunnel de conversion, campagnes sponsorisées, landing pages optimisées et suivi des performances.",
                'base_price_fcfa' => null,
            ],
            [
                'title' => 'Community management',
                'short_description' => 'Gestion professionnelle de vos réseaux sociaux.',
                'description' => "Publication régulière, création de visuels, modération des messages et suivi des statistiques : SWBS prend en main vos réseaux sociaux pour développer votre communauté et renforcer la relation avec vos clients.",
                'base_price_fcfa' => 40000,
            ],
            [
                'title' => 'Hébergement & maintenance',
                'short_description' => 'Hébergement sécurisé, sauvegardes et mises à jour techniques.',
                'description' => "Nous proposons un hébergement fiable et une maintenance proactive de vos sites et applications : mises à jour, correctifs, sauvegardes automatiques et surveillance.",
                'base_price_fcfa' => null,
            ],
            [
                'title' => 'Développement sur mesure',
                'short_description' => 'Solutions techniques personnalisées pour votre entreprise.',
                'description' => \"Lorsque les solutions standard ne suffisent plus, SWBS conçoit des fonctionnalités et modules sur mesure pour répondre précisément à vos besoins métier.\",
                'base_price_fcfa' => null,
            ],
            [
                'title' => 'E-commerce & solutions de paiement',
                'short_description' => 'Boutiques en ligne performantes et intégration des paiements.',
                'description' => \"Création de boutiques en ligne ergonomiques avec gestion des produits, du stock, des commandes et intégration des passerelles de paiement comme FedePay.\",
                'base_price_fcfa' => 250000,
            ],
        ];

        foreach ($services as $serviceData) {
            Service::updateOrCreate(
                ['slug' => Str::slug($serviceData['title'])],
                [
                    'title' => $serviceData['title'],
                    'short_description' => $serviceData['short_description'],
                    'description' => $serviceData['description'],
                    'base_price_fcfa' => $serviceData['base_price_fcfa'],
                    'is_active' => true,
                ]
            );
        }
    }
}