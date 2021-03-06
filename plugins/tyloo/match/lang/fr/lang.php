<?php

return [
    'plugin' => [
        'name' => 'Matchs',
        'description' => 'Permet de gérer les matchs de l\'équipe.',
    ],
    'match' => [
        'menu_label' => 'Matchs',
        'menu_description' => 'Gestion des matchs de l\'équipe',
        'matchs' => 'Matchs',
        'create_match' => 'match',
        'access_matchs' => 'Gérer les matchs',
        'access_other_matchs' => 'Gérer les matchs d\'autres utilisateurs',
        'delete_confirm' => 'Êtes-vous certain(e) de vouloir supprimer ?',
        'chart_published' => 'Publié',
        'chart_drafts' => 'Brouillons',
        'chart_total' => 'Total'
    ],
    'pages' => [
        'matchs' => [
            'list_title' => 'Gérer les matchs',
            'hide_published' => 'Masquer la publication',
            'new_match' => 'Nouveau match',
        ],
        'match' => [
            'title' => 'Titre',
            'title_placeholder' => 'Titre du nouveau match',
            'slug' => 'Adresse',
            'slug_placeholder' => 'adresse-du-nouveau-match',
            'team1' => 'Equipe à domicile',
            'team2' => 'Equipe à l\'extérieur',
            'team1_score' => 'Score Equipe à domicile',
            'team2_score' => 'Score Equipe à l\'extérieur',
            'created' => 'Créé',
            'updated' => 'Mis a jour',
            'published' => 'Publié',
            'published_validation' => 'Précisez s\'il vous plait la date de publication',
            'match_date' => 'Date du match',
            'match_location' => 'Lieu du match',
            'match_played' => 'Le match a été joué ?',
            'tab_edit' => 'Editer',
            'tab_manage' => 'Gestion',
            'published_on' => 'Publié le',
            'excerpt' => 'Résumé',
            'featured_images' => 'Image de sélection',
            'delete_confirm' => 'Souhaitez-vous vraiment supprimer ce match ?',
            'close_confirm' => 'Le match n\'est pas enregistré.',
            'return_to_matchs' => 'Retour à la liste des matchs',
        ],
    ],
    'settings' => [
        'match_title' => 'Match',
        'match_description' => 'Affiche un match sur la page.',
        'match_slug' => 'Adresse du match',
        'match_slug_description' => 'Adresse d\'accès au match.',
        'matchs_title' => 'Liste des matchs',
        'matchs_description' => 'Affiche une liste des derniers matchs sur la page.',
        'matchs_pagination' => 'Numéro de page',
        'matchs_pagination_description' => 'Cette valeur est utilisée pour déterminer a quelle pqge l\'utilisateur se trouve.',
        'matchs_filter' => 'Filtre des catégories',
        'matchs_filter_description' => 'Entrez une adresse de catégorie ou un paramètre d\'URL pour filter les matchs. Laissez vide pour afficher tous les matchs.',
        'matchs_per_page' => 'Nombre de matchs par page',
        'matchs_per_page_validation' => 'Format de matchs par page incorrect',
        'matchs_no_matchs' => 'Message en l\'absence de match',
        'matchs_no_matchs_description' => 'Message a afficher dans la liste des matchs lorsqu\'il n\'y a aucun match. Cette propriété est utilisée par le composant partiel par défaut.',
        'matchs_order' => 'Ordre des matchs',
        'matchs_order_decription' => 'Attribut selon lequel les matchs seront ordonnés',
        'matchs_match' => 'Page de match',
        'matchs_match_description' => 'Nom de la page de matchs pour les liens "En savoir plus". Cette propriété est utilisée par le composant partiel par défaut.',
    ],
];
