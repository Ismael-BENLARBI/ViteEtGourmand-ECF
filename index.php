<?php
session_start();

require_once 'config/db.php';

$page = $_GET['page'] ?? 'home';

switch($page) {
    case 'home':
        require_once 'Models/Menu.php';
        require_once 'Models/Avis.php'; // On charge le modèle

        $menus = Menu::getAll(); 
        $avisRecents = Avis::getLastThree(); // On récupère les avis
        
        require_once 'views/home/index.php';
        break;

    // --- PAGE NOS MENUS ---
    case 'menus':
        require_once 'Models/Menu.php';
        $filtreTheme = $_GET['theme'] ?? 'all';
        $menus = Menu::getAll($filtreTheme);
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

    // --- API : RÉCUPÉRER LES MENUS EN JSON (POUR AJAX) ---
    case 'api_menus':
        require_once 'Models/Menu.php';
        $filters = [
            'theme'  => $_GET['theme'] ?? 'all',
            'regime' => $_GET['regime'] ?? 'all',
            'prix'   => $_GET['prix'] ?? null,
            'pers'   => $_GET['pers'] ?? null
        ];
        $menus = Menu::getAll($filters);
        header('Content-Type: application/json');
        echo json_encode($menus);
        exit;
        break;

    // --- PAGE D'INSCRIPTION ---
    case 'register':
        require_once 'Views/auth/register.php';
        break;

    // --- TRAITEMENT DE L'INSCRIPTION ---
    case 'register_action':
        require_once 'Models/User.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $adresse = $_POST['adresse'];
            $cp = $_POST['cp'];
            $ville = $_POST['ville'];

            if (User::findByEmail($email)) {
                header('Location: index.php?page=register&error=Cet email est déjà utilisé');
                exit;
            }

            $success = User::create($nom, $prenom, $email, $pass, $adresse, $cp, $ville);

            if ($success) {
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

            $user = User::findByEmail($email);

            if ($user && password_verify($pass_input, $user['password'])) {
                
                $role_txt = 'client'; 
                if ($user['role_id'] == 1) {
                    $role_txt = 'admin';
                } elseif ($user['role_id'] == 2) {
                    $role_txt = 'employe';
                }

                $_SESSION['user'] = [
                    'id' => $user['utilisateur_id'],
                    'prenom' => $user['prenom'],
                    'nom' => $user['nom'],
                    'role' => $role_txt
                ];
                header('Location: index.php?page=home');
                exit;

            } else {
                header('Location: index.php?page=login&error=Email ou mot de passe incorrect');
                exit;
            }
        }
        break;

    // --- DECONNEXION ---
    case 'logout':
        session_destroy();
        header('Location: index.php?page=home');
        exit;
        break;

    // --- DASHBOARD ADMIN ---
    case 'admin_dashboard':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        
        require_once 'Models/Commande.php';
        require_once 'Models/Menu.php';
        require_once 'Models/Avis.php'; // <-- Important pour les avis

        // On récupère tout
        $commandes = Commande::getAll();
        $menus = Menu::getAll();
        $avisList = Avis::getAllAdmin(); // <-- Important pour les avis

        require_once 'Views/admin/dashboard.php';
        break;

    // --- ACTION : SUPPRIMER UNE COMMANDE ---
    case 'admin_commande_delete':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin(); // Sécurité : Seul l'admin peut supprimer
        require_once 'Models/Commande.php';

        if(isset($_GET['id'])) {
            Commande::delete($_GET['id']);
        }
        
        // Retour immédiat au tableau de bord
        header('Location: index.php?page=admin_dashboard');
        break;

    // --- ACTION : CHANGER LE STATUT D'UNE COMMANDE ---
    case 'admin_commande_status':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Commande.php';

        if(isset($_POST['id']) && isset($_POST['statut'])) {
            Commande::updateStatus($_POST['id'], $_POST['statut']);
            
            // Si c'est une requête AJAX, on répond en JSON et on s'arrête là
            if(isset($_POST['ajax'])) {
                echo json_encode(['status' => 'success', 'message' => 'Statut mis à jour !']);
                exit;
            }
        }
        header('Location: index.php?page=admin_dashboard');
        break;
    
    // --- PAGE AJOUTER MENU ---
    case 'admin_menu_add':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
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

    // --- PAGE : MODIFIER MENU (VUE) ---
    case 'admin_menu_edit':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Menu.php';

        if(isset($_GET['id'])){
            $menu = Menu::getById($_GET['id']);
            $themes = Menu::getThemes();
            $regimes = Menu::getRegimes();
            require_once 'Views/admin/menu_edit.php';
        }
        break;

    // --- ACTION : MODIFIER MENU (TRAITEMENT) ---
    case 'admin_menu_edit_action':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Menu.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
            $id = $_GET['id'];
            
            $titre = $_POST['titre'];
            $prix = $_POST['prix'];
            $min = $_POST['min_personnes'];
            $desc = $_POST['description'];
            $d_entree = $_POST['desc_entree'];
            $d_plat = $_POST['desc_plat'];
            $d_dessert = $_POST['desc_dessert'];
            $theme_id = !empty($_POST['theme_id']) ? $_POST['theme_id'] : NULL;
            $regime_id = !empty($_POST['regime_id']) ? $_POST['regime_id'] : NULL;

            function processImageUpdate($fileKey, $oldImageKey) {
                if (isset($_FILES[$fileKey]) && $_FILES[$fileKey]['error'] == 0) {
                    $targetDir = "assets/images/menu/";
                    $ext = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION);
                    $filename = 'menu_' . uniqid() . '.' . $ext;
                    if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetDir . $filename)) {
                        return $filename; 
                    }
                }
                return $_POST[$oldImageKey];
            }

            $img1 = processImageUpdate('image_principale', 'old_img_principale');
            $img2 = processImageUpdate('image_entree', 'old_img_entree');
            $img3 = processImageUpdate('image_plat', 'old_img_plat');
            $img4 = processImageUpdate('image_dessert', 'old_img_dessert');

            Menu::update($id, $titre, $desc, $d_entree, $d_plat, $d_dessert, $prix, $min, $theme_id, $regime_id, $img1, $img2, $img3, $img4);

            header('Location: index.php?page=admin_dashboard&success=Menu modifié !');
        }
        break;
    
    // --- ACTION : ENREGISTRER LE MENU (TRAITEMENT) ---
    case 'admin_menu_create_action':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin(); 

        require_once 'Models/Menu.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = $_POST['titre'];
            $prix = $_POST['prix'];
            $min_personnes = $_POST['min_personnes'];
            $description = $_POST['description'];
            $desc_entree = $_POST['desc_entree'];
            $desc_plat = $_POST['desc_plat'];
            $desc_dessert = $_POST['desc_dessert'];
            $theme_id = !empty($_POST['theme_id']) ? $_POST['theme_id'] : NULL;
            $regime_id = !empty($_POST['regime_id']) ? $_POST['regime_id'] : NULL;

            function uploadImage($fileKey) {
                if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] != 0) return NULL; 

                $targetDir = "assets/images/menu/";
                if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);

                $extension = pathinfo($_FILES[$fileKey]['name'], PATHINFO_EXTENSION);
                $filename = 'menu_' . uniqid() . '.' . $extension;
                
                if (move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetDir . $filename)) {
                    return $filename;
                }
                return NULL;
            }

            $img_principale = uploadImage('image_principale');
            $img_entree = uploadImage('image_entree');
            $img_plat = uploadImage('image_plat');
            $img_dessert = uploadImage('image_dessert');

            $success = Menu::create($titre, $description, $desc_entree, $desc_plat, $desc_dessert, $prix, $min_personnes, $theme_id, $regime_id, $img_principale, $img_entree, $img_plat, $img_dessert);

            if ($success) {
                header('Location: index.php?page=admin_dashboard&success=Menu ajouté avec succès !');
            } else {
                header('Location: index.php?page=admin_menu_add&error=Erreur lors de l\'enregistrement.');
            }
        }
        break;

    // --- PAGE : VOIR LE PANIER ---
    case 'panier':
        require_once 'Models/Menu.php';

        $panierComplet = []; 
        $totalGeneral = 0; 

        if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $id_menu => $quantite) {
                $menu = Menu::getById($id_menu);
                if ($menu) { 
                    $totalLigne = $menu['prix_par_personne'] * $quantite;
                    $panierComplet[] = [
                        'menu' => $menu,
                        'quantite' => $quantite,
                        'total_ligne' => $totalLigne
                    ];
                    $totalGeneral += $totalLigne;
                }
            }
        }
        require_once 'Views/front/panier.php';
        break;

    // --- AJOUTER AU PANIER ---
    case 'panier_add':
        if(isset($_POST['menu_id']) && isset($_POST['quantite'])) {
            $id = (int)$_POST['menu_id'];
            $qty = (int)$_POST['quantite'];

            if(!isset($_SESSION['panier'])) {
                $_SESSION['panier'] = [];
            }

            if(isset($_SESSION['panier'][$id])) {
                $_SESSION['panier'][$id] += $qty;
            } else {
                $_SESSION['panier'][$id] = $qty;
            }
            header('Location: index.php?page=panier');
            exit;
        } else {
            header('Location: index.php');
        }
        break;

    // --- ACTION : SUPPRIMER DU PANIER ---
    case 'panier_delete':
        if(isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            if(isset($_SESSION['panier'][$id])) {
                unset($_SESSION['panier'][$id]);
            }
        }
        header('Location: index.php?page=panier');
        break;

    // --- PAGE : TUNNEL DE COMMANDE ---
    case 'commande':
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login&error=Veuillez vous connecter pour valider votre commande.');
            exit;
        }
        if (empty($_SESSION['panier'])) {
            header('Location: index.php?page=panier');
            exit;
        }

        require_once 'Models/Menu.php';
        $totalPanier = 0;
        foreach ($_SESSION['panier'] as $id => $qty) {
            $menu = Menu::getById($id);
            if($menu) $totalPanier += $menu['prix_par_personne'] * $qty;
        }

        require_once 'Views/front/commande.php';
        break;

    // --- ACTION : VALIDER LA COMMANDE ---
    case 'commande_validation':
        require_once 'Utils/Auth.php';
        if (!isset($_SESSION['user'])) { header('Location: index.php?page=login'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['panier'])) {
            require_once 'Models/Commande.php';
            require_once 'Models/Menu.php';

            $userId = $_SESSION['user']['id'];
            
            $totalFinal = $_POST['total_final'];
            $frais = $_POST['frais_livraison'];
            $reduction = $_POST['montant_reduction'];

            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $adresse = $_POST['adresse'];
            $cp = $_POST['cp'];
            $ville = $_POST['ville'];
            $phone = $_POST['phone'];
            $heure = $_POST['heure_livraison'];
            $instructions = $_POST['instructions'];

            $commandeId = Commande::create($userId, $totalFinal, $frais, $reduction, $nom, $prenom, $adresse, $cp, $ville, $phone, $heure, $instructions);

            foreach ($_SESSION['panier'] as $menuId => $qty) {
                $menu = Menu::getById($menuId);
                if ($menu) {
                    Commande::addDetail($commandeId, $menuId, $qty, $menu['prix_par_personne']);
                }
            }

            unset($_SESSION['panier']);
            header('Location: index.php?page=commande_success');
            exit;
        } else {
            header('Location: index.php');
        }
        break;

    // --- PAGE : SUCCÈS COMMANDE ---
    case 'commande_success':
        require_once 'Views/front/commande_success.php';
        break;

    // --- PAGE : DÉTAILS D'UNE COMMANDE ---
    case 'commande_details':
        require_once 'Utils/Auth.php';
        // 1. Il faut être connecté
        if (!isset($_SESSION['user'])) { header('Location: index.php?page=login'); exit; }
        
        // 2. Il faut un ID dans l'URL
        if (!isset($_GET['id']) || empty($_GET['id'])) {
            header('Location: index.php?page=compte');
            exit;
        }

        require_once 'Models/Commande.php';
        $commandeId = $_GET['id'];
        $userId = $_SESSION['user']['id'];
        $userRole = $_SESSION['user']['role'] ?? 'client'; // On récupère le rôle

        // 3. On récupère les infos de la commande
        $commande = Commande::getById($commandeId);

        if (!$commande) {
            header('Location: index.php?page=compte');
            exit;
        }

        // 4. SÉCURITÉ MISE À JOUR :
        // On autorise si c'est le propriétaire de la commande OU si c'est un admin
        if ($commande['utilisateur_id'] != $userId && $userRole !== 'admin') {
            // Si ce n'est NI le propriétaire NI l'admin -> Dehors !
            header('Location: index.php?page=compte');
            exit;
        }

        // 5. Tout est bon, on affiche
        $details = Commande::getDetails($commandeId);

        require_once 'Views/front/commande_detail.php';
        break;

    // --- PAGE : MON COMPTE ---
    case 'compte':
        require_once 'Utils/Auth.php';
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?page=login');
            exit;
        }

        require_once 'Models/Commande.php';
        $userId = $_SESSION['user']['id']; 
        $commandes = Commande::getAllByUser($userId); // On renomme en $commandes pour le nouveau template

        require_once 'Views/front/compte.php';
        break;

    // --- ACTION : AJOUTER UN AVIS ---
    case 'avis_add':
        require_once 'Utils/Auth.php';
        if (!isset($_SESSION['user'])) { header('Location: index.php?page=login'); exit; }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'Models/Avis.php';
            
            $menuId = $_POST['menu_id'];         // C'est désormais un menu
            $userId = $_SESSION['user']['id'];
            $note = $_POST['note'];
            $description = $_POST['description']; // Ton champ s'appelle 'description'

            Avis::create($menuId, $userId, $note, $description);

            // On redirige vers la commande précédente
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&success=Avis enregistré !');
        }
        break;

    // --- ACTION : VALIDER UN AVIS ---
    case 'admin_avis_validate':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Avis.php';
        
        if(isset($_GET['id'])) {
            Avis::validate($_GET['id']);
            
            if(isset($_GET['ajax'])) {
                echo json_encode(['status' => 'success', 'message' => 'Avis validé !']);
                exit;
            }
        }
        header('Location: index.php?page=admin_dashboard');
        break;

    // --- ACTION : SUPPRIMER UN AVIS ---
    case 'admin_avis_delete':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Avis.php';
        
        if(isset($_GET['id'])) {
            Avis::delete($_GET['id']);
            
            if(isset($_GET['ajax'])) {
                echo json_encode(['status' => 'success', 'message' => 'Avis supprimé !']);
                exit;
            }
        }
        header('Location: index.php?page=admin_dashboard');
        break;

    // --- ACTION : MODIFIER SON PROFIL ---
    case 'compte_update':
        require_once 'Utils/Auth.php';
        if (!isset($_SESSION['user'])) { header('Location: index.php?page=login'); exit; }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'Models/User.php';
            
            $id = $_SESSION['user']['id']; // On prend l'ID de la session par sécurité
            
            // Mise à jour en BDD
            User::update(
                $id,
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['email'],
                $_POST['telephone'],
                $_POST['adresse'],
                $_POST['code_postal'],
                $_POST['ville']
            );

            // Mise à jour de la SESSION (pour affichage immédiat)
            $userFresh = User::getById($id);
            $_SESSION['user']['nom'] = $userFresh['nom'];
            $_SESSION['user']['prenom'] = $userFresh['prenom'];
            $_SESSION['user']['role'] = $userFresh['role']; // On garde le role
            // Tu peux stocker d'autres infos en session si besoin

            header('Location: index.php?page=compte&success=Profil mis à jour !');
        }
        break;

    default:
        http_response_code(404);
        echo "<h1 style='text-align:center; padding-top:100px;'>404 - Page introuvable</h1>";
        break;
}
?>