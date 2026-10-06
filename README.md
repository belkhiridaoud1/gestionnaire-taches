# Gestionnaire de tâches

Application web de gestion de tâches développée en PHP et MySQL.

## 📋 Présentation

Cette application permet de créer, modifier, supprimer et suivre des tâches de manière simple.

## 🚀 Fonctionnalités

- Dashboard
- Création de tâches
- Modification des tâches
- Suppression des tâches
- Gestion des catégories
- Recherche de tâches
- Filtrage par statut
- Gestion des priorités
- Date limite des tâches
- Marquage des tâches comme terminées

## 🛠️ Technologies utilisées

- PHP 8.2+
- MySQL
- HTML5
- CSS3
- PDO
- XAMPP
- Git

## ⚙️ Installation

### Prérequis

- PHP 8.2+
- MySQL
- Apache
- XAMPP
- Git

### Installation

1. Cloner le repository :

```bash
git clone https://github.com/belkhiridaoud1/gestionnaire-taches.git

2. Placer le projet dans le dossier htdocs de XAMPP.
3. Importer le fichier database.sql dans phpMyAdmin.
4. Vérifier la configuration dans config/database.php.
5. Démarrer Apache et MySQL depuis XAMPP.
6. Ouvrir :
http://localhost/gestionnaire-taches/

Structure du projet
gestionnaire-taches/
├── categories/
├── tasks/
├── config/
├── assets/
├── database.sql
├── index.php
├── README.md
└── .gitignore