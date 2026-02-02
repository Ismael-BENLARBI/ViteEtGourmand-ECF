<?php
session_start();

require_once 'config/db.php';

$page = $_GET['page'] ?? 'home';

switch($page) {
    case 'home':
        require_once 'views/home/index.php';
        break;

    // --- PAGE NOS MENUS ---
    case 'menus':
        require_once 'Models/Menu.php';
        
        // 1. On regarde si l'utilisateur a filtré (via l'URL ?theme=...)
        $filtreTheme = $_GET['theme'] ?? 'all';
        
        // 2. On récupère les menus filtrés (ou tous si $filtreTheme vaut 'all')
        $menus = Menu::getAll($filtreTheme);
        
        // 3. On récupère la liste des thèmes pour remplir le select
        $themes = Menu::getThemes();
        
        require_once 'Views/menus/index.php';
        break;

    // --- PAGE DÉTAIL D'UN MENU ---
    case 'menu':
        require_once 'Models/Menu.php';
        
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $menu = Menu::getById($id);
            $plats = Menu::getPlatsByMenuId($id);
            
            if ($menu) {
                // ICI : On appelle le nouveau fichier
                require_once 'Views/menus/detail_menu.php'; 
            } else {
                header('Location: index.php?page=menus');
            }
        } else {
            header('Location: index.php?page=menus');
        }
        break;

    case 'contact':
        require_once 'views/contact/index.php';
        break;

    case 'login':
        require_once 'views/auth/login.php'; 
        break;

    case 'profil':
        require_once 'views/users/profil.php'; 
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?page=home');
        exit;
        break;

    // --- API : RÉCUPÉRER LES MENUS EN JSON (POUR AJAX) ---
    case 'api_menus':
        require_once 'Models/Menu.php';
        
        // On prépare le tableau de filtres pour le modèle
        $filters = [
            'theme'  => $_GET['theme'] ?? 'all',
            'regime' => $_GET['regime'] ?? 'all',
            'prix'   => $_GET['prix'] ?? null,
            'pers'   => $_GET['pers'] ?? null
        ];
        
        // On appelle la nouvelle version de getAll
        $menus = Menu::getAll($filters);
        
        header('Content-Type: application/json');
        echo json_encode($menus);
        exit;
        break;

    // --- PAGE D'INSCRIPTION (Affichage du formulaire) ---
    case 'register':
        require_once 'Views/auth/register.php';
        break;

    // --- TRAITEMENT DE L'INSCRIPTION ---
    case 'register_action':
        require_once 'Models/User.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Récupération des données du formulaire
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $adresse = $_POST['adresse']; // On récupère l'input 'adresse'
            $cp = $_POST['cp'];
            $ville = $_POST['ville'];

            // 2. Vérifier si l'email existe déjà
            if (User::findByEmail($email)) {
                header('Location: index.php?page=register&error=Cet email est déjà utilisé');
                exit;
            }

            // 3. Création du compte via le Modèle
            // Le modèle va mettre 'adresse' dans la colonne 'adresse_postale'
            $success = User::create($nom, $prenom, $email, $pass, $adresse, $cp, $ville);

            if ($success) {
                // Si ça marche, on envoie vers le login (qu'on fera juste après)
                header('Location: index.php?page=login&success=Compte créé ! Connectez-vous.');
            } else {
                header('Location: index.php?page=register&error=Erreur technique lors de la création.');
            }
        }
        break;

    // --- PAGE LOGIN (Affichage) ---
    case 'login':
        require_once 'Views/auth/login.php';
        break;

    // --- ACTION LOGIN (Traitement) ---
    case 'login_action':
        require_once 'Models/User.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $pass_input = $_POST['password'];

            // 1. Chercher l'utilisateur
            $user = User::findByEmail($email);

            // 2. Vérification (Si user existe ET si mot de passe correspond au hachage)
            // Note : ta colonne s'appelle 'password' dans la BDD
            if ($user && password_verify($pass_input, $user['password'])) {
                
                // 3. SUCCÈS : Création de la Session
                // On traduit le role_id en texte pour faciliter la suite (1 = admin, le reste = client)
                $role_txt = 'client'; // Par défaut
            
                if ($user['role_id'] == 1) {
                    $role_txt = 'admin';
                } elseif ($user['role_id'] == 2) {
                    $role_txt = 'employe';
                }
                // Sinon reste 'client' (pour le 3)

                $_SESSION['user'] = [
                    'id' => $user['utilisateur_id'],
                    'prenom' => $user['prenom'],
                    'nom' => $user['nom'],
                    'role' => $role_txt
                ];
                // Redirection vers l'accueil
                header('Location: index.php?page=home');
                exit;

            } else {
                // ECHEC
                header('Location: index.php?page=login&error=Email ou mot de passe incorrect');
                exit;
            }
        }
        break;

        // --- DASHBOARD ADMIN (Protégé) ---
    case 'admin_dashboard':
        // 1. Sécurité (Le Vigile)
        require_once 'Utils/Auth.php';
        Auth::checkAdmin(); 
        
        // 2. Récupération des données (C'est ce qui manquait !)
        require_once 'Models/Menu.php'; // On charge le modèle
        $menus = Menu::getAll();        // On récupère la liste des menus dans la variable $menus
        
        // 3. Affichage (La Vue)
        require_once 'Views/admin/dashboard.php'; // La vue va utiliser la variable $menus créée juste au-dessus
        break;
    
    // --- PAGE AJOUTER MENU (Formulaire) ---
    case 'admin_menu_add':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin(); // Sécurité
        require_once 'Views/admin/menu_add.php';
        break;

    // --- ACTION : SUPPRIMER MENU ---
    case 'admin_menu_delete':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Menu.php';

        if (isset($_GET['id'])) {
            Menu::delete($_GET['id']);
            header('Location: index.php?page=admin_dashboard&success=Menu supprimé !');
        } else {
            header('Location: index.php?page=admin_dashboard&error=ID manquant.');
        }
        break;
    
    // --- ACTION : ENREGISTRER LE MENU (TRAITEMENT) ---
    case 'admin_menu_create_action':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin(); // Sécurité : Seul l'admin peut faire ça

        require_once 'Models/Menu.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Récupération des textes (y compris les nouveaux champs)
            $titre = $_POST['titre'];
            $prix = $_POST['prix'];
            $min_personnes = $_POST['min_personnes'];
            $description = $_POST['description']; // L'accroche
            
            $desc_entree = $_POST['desc_entree'];
            $desc_plat = $_POST['desc_plat'];
            $desc_dessert = $_POST['desc_dessert'];

            $theme_id = !empty($_POST['theme_id']) ? $_POST['theme_id'] : NULL;

            $regime_id = !empty($_POST['regime_id']) ? $_POST['regime_id'] : NULL;
            // 2. Fonction locale pour gérer l'upload d'image
            function uploadImage($fileKey) {
                // Si pas de fichier ou erreur
                if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] != 0) {
                    return NULL; 
                }

                // Création du dossier si besoin
                $targetDir = "assets/images/menu/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                // Nom unique pour éviter d'écraser les fichiers
                $extension = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION);
                $filename = 'menu_' . uniqid() . '.' . $extension;
                
                // Déplacement
                if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetDir . $filename)) {
                    return $filename;
                }
                return NULL;
            }

            // On lance les uploads pour les 4 images
            $img_principale = uploadImage('image_principale');
            $img_entree = uploadImage('image_entree');
            $img_plat = uploadImage('image_plat');
            $img_dessert = uploadImage('image_dessert');

            // 3. Enregistrement en Base de Données avec TOUTES les infos
            $success = Menu::create(
                $titre, 
                $description, 
                $desc_entree, 
                $desc_plat, 
                $desc_dessert, 
                $prix, 
                $min_personnes, 
                $theme_id,
                $regime_id,
                $img_principale, 
                $img_entree, 
                $img_plat, 
                $img_dessert
            );

            if ($success) {
                header('Location: index.php?page=admin_dashboard&success=Menu ajouté avec succès !');
            } else {
                header('Location: index.php?page=admin_menu_add&error=Erreur lors de l\'enregistrement.');
            }
        }
        break;
    
    // --- DECONNEXION ---
    case 'logout':
        session_destroy();
        header('Location: index.php?page=login');
        exit;
        break;

    default:
        http_response_code(404);
        echo "<h1 style='text-align:center; padding-top:100px;'>404 - Page introuvable</h1>";
        break;
}
?>