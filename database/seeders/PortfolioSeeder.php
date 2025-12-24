<?php

namespace Database\Seeders;

use App\Models\Portfolio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PortfolioSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Site vitrine pour PME locale',
                'service_type' => 'creation-de-sites-web-professionnels',
                'excerpt' => "Un site vitrine clair et moderne pour une PME locale souhaitant renforcer sa présence en ligne.",
                'description' => "Conception d'un site vitrine responsive présentant les services, les réalisations et les coordonnées de l'entreprise. Intégration d'un formulaire de contact optimisé et d'appels à l'action clairs pour générer des demandes de devis.",
                'client_name' => 'PME locale',
                'url' => null,
            ],
            [
                'title' => 'Plateforme de réservation en ligne',
                'service_type' => 'conception-dapplications-web',
                'excerpt' => "Une plateforme web permettant aux clients de réserver des prestations en quelques clics.",
                'description' => "Développement d'une application web avec gestion des créneaux, notifications email, interface d'administration et suivi des réservations. L'objectif : réduire les appels téléphoniques et automatiser la prise de rendez-vous.",
                'client_name' => 'Entreprise de services',
                'url' => null,
            ],
            [
                'title' => 'Identité visuelle complète pour une marque',
                'service_type' => 'identite-visuelle-branding',
                'excerpt' => "Création de l'univers de marque complet pour une nouvelle entreprise.",
                'description' => "Logo, palette de couleurs, typographie, cartes de visite et bannières pour les réseaux sociaux. Un branding cohérent qui renforce la crédibilité de la marque dès ses premiers contacts avec le public.",
                'client_name' => 'Marque émergente',
                'url' => null,
            ],
            [
                'title' => 'Application métier pour entreprise',
                'service_type' => 'developpement-sur-mesure',
                'excerpt' => "Application interne pour centraliser les processus métiers d'une PME.",
                'description' => "Outil métier développé sur mesure pour gérer les clients, les devis, les commandes et le suivi des paiements. Résultat : une meilleure visibilité sur l'activité et un gain de temps important au quotidien.",
                'client_name' => 'Entreprise B2B',
                'url' => null,
            ],
            [
                'title' => 'Boutique e-commerce',
                'service_type' => 'e-commerce-solutions-de-paiement',
                'excerpt' => "Boutique en ligne avec paiement sécurisé et gestion des commandes.",
                'description' => "Mise en place d'une boutique e-commerce avec catalogue produits, panier, tunnel de commande et intégration de solutions de paiement adaptées au marché local.",
                'client_name' => 'Commerçant en ligne',
                'url' => null,
            ],
            [
                'title' => 'Gestion commerciale sur mesure',
                'service_type' => 'developpement-sur-mesure',
                'excerpt' => "Outil complet de gestion commerciale, du premier contact à la facturation.",
                'description' => "Cette solution regroupe la gestion des prospects, des offres, des contrats et des factures, avec des tableaux de bord pour suivre les performances commerciales.",
                'client_name' => 'Agence de services',
                'url' => null,
            ],
        ];

        foreach ($items as $item) {
            Portfolio::updateOrCreate(
                ['slug' => Str::slug($item['title'])],
                [
                    'title' => $item['title'],
                    'excerpt' => $item['excerpt'],
                    'description' => $item['description'],
                    'service_type' => $item['service_type'],
                    'client_name' => $item['client_name'],
                    'image_path' => null,
                    'url' => $item['url'],
                    'is_featured' => true,
                ]
            );
        }
    }
}