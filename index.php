<?php
session_start();

require_once 'config/db.php';

$page = $_GET['page'] ?? 'home';

switch($page) {
    
    case 'home':
        require_once 'Models/Menu.php';
        require_once 'Models/Avis.php';
        $menus = Menu::getAll(); 
        $avisRecents = Avis::getLastThree(); 
        require_once 'Views/home/index.php';
        break;

    case 'menus':
        require_once 'Models/Menu.php';
        $filtreTheme = $_GET['theme'] ?? 'all';
        $menus = Menu::getAll($filtreTheme);
        $themes = Menu::getThemes();
        require_once 'Views/menus/index.php';
        break;

    case 'menu':
        require_once 'Models/Menu.php';
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $menu = Menu::getById($id);
            
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
        require_once 'Views/front/contact.php';
        break;

    case 'contact_submit':
        require_once 'Models/Contact.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $email = htmlspecialchars($_POST['email']);
            $sujet = htmlspecialchars($_POST['sujet']);
            $contenu = htmlspecialchars($_POST['contenu']);

            Contact::add($nom, $email, $sujet, $contenu);

            $to = $email;
            $emailSujet = "Confirmation de réception - Vite & Gourmand";
            $message = "Bonjour $nom,\n\nNous avons bien reçu votre message.\n\nCordialement,\nL'équipe.";
            $headers = "From: no-reply@viteetgourmand.com";
            @mail($to, $emailSujet, $message, $headers);

            header('Location: index.php?page=contact&success=1');
        }
        break;

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

    case 'register':
        require_once 'Views/auth/register.php';
        break;

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

            if (User::create($nom, $prenom, $email, $pass, $adresse, $cp, $ville)) {
                header('Location: index.php?page=login&success=Compte créé ! Connectez-vous.');
            } else {
                header('Location: index.php?page=register&error=Erreur technique.');
            }
        }
        break;

    case 'login':
        require_once 'Views/auth/login.php';
        break;

    case 'login_action':
        require_once 'Models/User.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $pass_input = $_POST['password'];

            $user = User::findByEmail($email);

            if ($user && password_verify($pass_input, $user['password'])) {
                
                $_SESSION['user'] = [
                    'id' => $user['utilisateur_id'],
                    'prenom' => $user['prenom'],
                    'nom' => $user['nom'],
                    'email' => $user['email'],
                    'role_id' => $user['role_id']
                ];

                require_once 'Models/Panier.php';
                
                $panierBdd = Panier::loadFromUser($user['utilisateur_id']);
                
                if (!isset($_SESSION['panier'])) $_SESSION['panier'] = [];

                foreach($panierBdd as $mid => $qty) {
                    if (isset($_SESSION['panier'][$mid])) {
                        $_SESSION['panier'][$mid] += $qty;
                    } else {
                        $_SESSION['panier'][$mid] = $qty;
                    }
                }

                Panier::saveForUser($user['utilisateur_id'], $_SESSION['panier']);

                if ($user['role_id'] == 1) {
                    header('Location: index.php?page=admin_dashboard');
                } elseif ($user['role_id'] == 2) {
                    header('Location: index.php?page=employe_dashboard');
                } else {
                    header('Location: index.php?page=home');
                }
                exit;

            } else {
                header('Location: index.php?page=login&error=Email ou mot de passe incorrect');
                exit;
            }
        }
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?page=home');
        exit;
        break;

    case 'forgot_password':
        require_once 'Views/auth/forgot_password.php';
        break;

    case 'forgot_password_submit':
        require_once 'Models/User.php';
        require_once 'Models/Mailer.php'; 

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            
            $user = User::getByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                User::setResetToken($email, $token);

                $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
                $path = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $link = $protocol . $_SERVER['HTTP_HOST'] . $path . "/index.php?page=reset_password&token=" . $token;

                $sujet = "Réinitialisation de votre mot de passe - Vite & Gourmand";
                $message = "
                    <html>
                    <body>
                        <h3>Bonjour " . htmlspecialchars($user['prenom']) . ",</h3>
                        <p>Vous avez demandé à réinitialiser votre mot de passe.</p>
                        <p>Cliquez sur le lien ci-dessous pour en créer un nouveau (valable 1h) :</p>
                        <p>
                            <a href='$link' style='background-color: #D8A85E; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>
                                Réinitialiser mon mot de passe
                            </a>
                        </p>
                        <p><small>Ou copiez ce lien : $link</small></p>
                    </body>
                    </html>
                ";

                Mailer::send($email, $sujet, $message);
            }

            header('Location: index.php?page=forgot_password&success=1');
        }
        break;

    case 'reset_password':
        require_once 'Models/User.php';
        if (isset($_GET['token']) && User::getUserByResetToken($_GET['token'])) {
            require_once 'Views/auth/reset_password.php';
        } else {
            echo "Lien invalide.";
        }
        break;

    case 'reset_password_submit':
        require_once 'Models/User.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['token'];
            $pass = $_POST['password'];
            $user = User::getUserByResetToken($token);
            if ($user) {
                User::updatePassword($user['utilisateur_id'], $pass);
                User::clearResetToken($user['utilisateur_id']);
                header('Location: index.php?page=login&success=password_reset');
            } else {
                echo "Erreur Token.";
            }
        }
        break;

    case 'panier':
        require_once 'Models/Menu.php';
        $panierComplet = []; 
        $totalGeneral = 0; 
        
        if (isset($_SESSION['panier']) && !empty($_SESSION['panier'])) {
            foreach ($_SESSION['panier'] as $id_menu => $quantite) {
                $menu = Menu::getById($id_menu);
                
                if ($menu) { 
                    $totalLigne = $menu['prix_par_personne'] * $quantite;
                    $panierComplet[] = ['menu' => $menu, 'quantite' => $quantite, 'total_ligne' => $totalLigne];
                    $totalGeneral += $totalLigne;
                } else {
                    unset($_SESSION['panier'][$id_menu]);
                    // -----------------------------
                }
            }
        }
        require_once 'Views/front/panier.php';
        break;

    case 'panier_add':
        if(isset($_POST['menu_id']) && isset($_POST['quantite'])) {
            $id = (int)$_POST['menu_id'];
            $qty = (int)$_POST['quantite'];
            
            if(!isset($_SESSION['panier'])) $_SESSION['panier'] = [];
            
            if(isset($_SESSION['panier'][$id])) $_SESSION['panier'][$id] += $qty;
            else $_SESSION['panier'][$id] = $qty;

            if(isset($_SESSION['user'])) {
                require_once 'Models/Panier.php';
                Panier::saveForUser($_SESSION['user']['id'], $_SESSION['panier']);
            }

            header('Location: index.php?page=panier');
            exit;
        }
        header('Location: index.php');
        break;

    case 'panier_delete':
        if(isset($_GET['id'])) { 
            unset($_SESSION['panier'][(int)$_GET['id']]); 
            
            if(isset($_SESSION['user'])) {
                require_once 'Models/Panier.php';
                Panier::saveForUser($_SESSION['user']['id'], $_SESSION['panier']);
            }
        }
        header('Location: index.php?page=panier');
        break;

    case 'commande':
        require_once 'Utils/Auth.php';
        Auth::check();
        if (empty($_SESSION['panier'])) { header('Location: index.php?page=panier'); exit; }
        
        require_once 'Models/Menu.php';
        $totalPanier = 0;
        foreach ($_SESSION['panier'] as $id => $qty) {
            $menu = Menu::getById($id);
            if($menu) $totalPanier += $menu['prix_par_personne'] * $qty;
        }
        require_once 'Views/front/commande.php';
        break;

    case 'commande_validation':
        require_once 'Utils/Auth.php';
        Auth::check();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['panier'])) {
            require_once 'Models/Commande.php';
            require_once 'Models/Menu.php';
            require_once 'Models/Panier.php'; 
            require_once 'Models/Mailer.php';

            $userId = $_SESSION['user']['id'];
            
            $commandeId = Commande::create($userId, $_POST['total_final'], $_POST['frais_livraison'], $_POST['montant_reduction'], $_POST['nom'], $_POST['prenom'], $_POST['adresse'], $_POST['cp'], $_POST['ville'], $_POST['phone'], $_POST['heure_livraison'], $_POST['instructions'],$_POST['date_prestation'] );

            $sujet = "Confirmation de votre commande #" . $commandeId . " - Vite & Gourmand";
            $messageHTML = "
                <html>
                <body style='font-family: Arial, sans-serif; color: #333;'>
                    <div style='background-color: #f8f9fa; padding: 20px; text-align: center; border-bottom: 3px solid #D8A85E;'>
                        <h2 style='color: #D8A85E; margin:0;'>Vite & Gourmand</h2>
                        <p>Merci pour votre commande !</p>
                    </div>
                    
                    <div style='padding: 20px;'>
                        <p>Bonjour <strong>" . htmlspecialchars($_POST['prenom']) . "</strong>,</p>
                        <p>Votre commande a bien été enregistrée. Voici le récapitulatif :</p>
                        
                        <div style='background-color: #fdfdfd; padding: 15px; border: 1px solid #eee; margin-bottom: 20px;'>
                            <h4 style='margin-top: 0;'>📅 Livraison prévue</h4>
                            <p style='margin: 0;'>
                                Le <strong>" . htmlspecialchars($_POST['date_prestation']) . "</strong> à <strong>" . htmlspecialchars($_POST['heure_livraison']) . "</strong><br>
                                À l'adresse : " . htmlspecialchars($_POST['adresse']) . ", " . htmlspecialchars($_POST['cp']) . " " . htmlspecialchars($_POST['ville']) . "
                            </p>
                        </div>

                        <table width='100%' border='0' cellspacing='0' cellpadding='10' style='border-collapse: collapse; border: 1px solid #ddd;'>
                            <tr style='background-color: #eee;'>
                                <th style='text-align: left; border-bottom: 1px solid #ddd;'>Menu</th>
                                <th style='text-align: center; border-bottom: 1px solid #ddd;'>Quantité</th>
                                <th style='text-align: right; border-bottom: 1px solid #ddd;'>Total</th>
                            </tr>
            ";

            foreach ($_SESSION['panier'] as $menuId => $qty) {
                $menu = Menu::getById($menuId);
                
                if ($menu) {
                    Commande::addDetail($commandeId, $menuId, $qty, $menu['prix_par_personne']);
                    Menu::decrementStock($menuId, $qty);

                    $prixLigne = $menu['prix_par_personne'] * $qty;
                    $messageHTML .= "
                        <tr>
                            <td style='border-bottom: 1px solid #ddd;'>" . htmlspecialchars($menu['titre']) . "</td>
                            <td style='border-bottom: 1px solid #ddd; text-align: center;'>" . $qty . "</td>
                            <td style='border-bottom: 1px solid #ddd; text-align: right;'>" . number_format($prixLigne, 2) . " €</td>
                        </tr>
                    ";
                }
            }

            $messageHTML .= "
                            <tr style='background-color: #f9f9f9; font-weight: bold;'>
                                <td colspan='2' style='text-align: right; border-top: 2px solid #ddd;'>TOTAL PAYÉ</td>
                                <td style='text-align: right; border-top: 2px solid #ddd; color: #D8A85E; font-size: 18px;'>
                                    " . htmlspecialchars($_POST['total_final']) . " €
                                </td>
                            </tr>
                        </table>
                        
                        <p style='margin-top: 30px; font-size: 12px; color: #777;'>
                            Si vous avez la moindre question, n'hésitez pas à répondre directement à cet email.
                        </p>
                    </div>
                </body>
                </html>
            ";

            Mailer::send($_SESSION['user']['email'], $sujet, $messageHTML);

            Panier::clearSavedCart($userId);
            unset($_SESSION['panier']);
            
            header('Location: index.php?page=commande_success');
            exit;
        }
        header('Location: index.php');
        break;

    case 'commande_success':
        require_once 'Views/front/commande_success.php';
        break;

    case 'commande_details':
        require_once 'Utils/Auth.php';
        Auth::check();
        require_once 'Models/Commande.php';
        $commandeId = $_GET['id'];
        $commande = Commande::getById($commandeId);
        
        $isStaff = isset($_SESSION['user']['role_id']) && in_array($_SESSION['user']['role_id'], [1, 2]);
        if ($commande['utilisateur_id'] != $_SESSION['user']['id'] && !$isStaff) {
            header('Location: index.php?page=compte'); exit;
        }
        $details = Commande::getDetails($commandeId);
        require_once 'Views/front/commande_detail.php';
        break;

    case 'compte':
        require_once 'Utils/Auth.php';
        Auth::check();
        require_once 'Models/Commande.php';
        $commandes = Commande::getAllByUser($_SESSION['user']['id']);
        require_once 'Views/front/compte.php';
        break;

    case 'compte_update':
        require_once 'Utils/Auth.php';
        Auth::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'Models/User.php';
            $id = $_SESSION['user']['id'];
            User::update($id, $_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['telephone'], $_POST['adresse'], $_POST['code_postal'], $_POST['ville']);
            
            $_SESSION['user']['nom'] = $_POST['nom'];
            $_SESSION['user']['prenom'] = $_POST['prenom'];
            
            header('Location: index.php?page=compte&success=Profil mis à jour');
        }
        break;

    case 'compte_update_password':
        require_once 'Utils/Auth.php';
        Auth::check();
        require_once 'Models/User.php';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_SESSION['user']['id'];
            if (!User::verifyPassword($id, $_POST['old_password'])) {
                header('Location: index.php?page=compte&error=wrong_pass'); exit;
            }
            if ($_POST['new_password'] !== $_POST['confirm_password']) {
                header('Location: index.php?page=compte&error=mismatch'); exit;
            }
            User::updatePassword($id, $_POST['new_password']);
            header('Location: index.php?page=compte&success=pass_updated');
        }
        break;

    case 'client_order_cancel':
        require_once 'Utils/Auth.php';
        Auth::check(); 
        require_once 'Models/Commande.php';
        require_once 'Models/Menu.php';

        if(isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $cmd = Commande::getById($id);

            if($cmd && $cmd['utilisateur_id'] == $_SESSION['user']['id'] && $cmd['statut'] == 'en_attente') {
                
                $details = Commande::getDetails($id);
                foreach($details as $d) {
                    Menu::incrementStock($d['menu_id'], $d['quantite']);
                }

                Commande::updateStatus($id, 'annulee');
                header('Location: index.php?page=compte&success=Commande annulée');
            } else {
                header('Location: index.php?page=compte&error=Action impossible');
            }
            exit;
        }
        break;

    case 'client_order_modify':
        require_once 'Utils/Auth.php';
        Auth::check();
        require_once 'Models/Commande.php';
        require_once 'Models/Panier.php';
        require_once 'Models/Menu.php';

        if(isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $cmd = Commande::getById($id);

            if($cmd && $cmd['utilisateur_id'] == $_SESSION['user']['id'] && $cmd['statut'] == 'en_attente') {
                
                $details = Commande::getDetails($id);

                $_SESSION['panier'] = [];
                foreach($details as $d) {
                    $_SESSION['panier'][$d['menu_id']] = $d['quantite'];
                    
                    Menu::incrementStock($d['menu_id'], $d['quantite']);
                }

                Panier::saveForUser($_SESSION['user']['id'], $_SESSION['panier']);
                Commande::updateStatus($id, 'annulee');

                header('Location: index.php?page=panier&success=Commande modifiable dans le panier');
            } else {
                header('Location: index.php?page=compte&error=Action impossible');
            }
            exit;
        }
        break;

    case 'avis_add':
        require_once 'Utils/Auth.php';
        Auth::check();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once 'Models/Avis.php';
            Avis::create($_POST['menu_id'], $_SESSION['user']['id'], $_POST['note'], $_POST['description']);
            header('Location: ' . $_SERVER['HTTP_REFERER'] . '&success=Avis enregistré');
        }
        break;

    case 'admin_menu_add':
    case 'admin_menu_edit':
        require_once 'Utils/Auth.php';
        Auth::checkStaff();
        require_once 'Models/Menu.php';
        $themes = Menu::getThemes();
        $regimes = Menu::getRegimes();
        if($page == 'admin_menu_edit' && isset($_GET['id'])) {
            $menu = Menu::getById($_GET['id']);
            require_once 'Views/admin/menu_edit.php';
        } else {
            require_once 'Views/admin/menu_add.php';
        }
        break;

    case 'admin_menu_create_action':
    case 'admin_menu_edit_action':
        require_once 'Utils/Auth.php';
        Auth::checkStaff();
        require_once 'Models/Menu.php';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            function getUploadedImageOrKeepOld($inputName) {
                if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
                    $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
                    $filename = 'menu_' . uniqid() . '.' . $ext;
                    move_uploaded_file($_FILES[$inputName]['tmp_name'], "assets/images/menu/" . $filename);
                    return $filename;
                }
                $oldKey = 'old_' . $inputName;
                return !empty($_POST[$oldKey]) ? $_POST[$oldKey] : null;
            }

            $img1 = getUploadedImageOrKeepOld('image_principale');
            $img2 = getUploadedImageOrKeepOld('image_entree');
            $img3 = getUploadedImageOrKeepOld('image_plat');
            $img4 = getUploadedImageOrKeepOld('image_dessert');

            if ($page == 'admin_menu_create_action') {
                Menu::create(
                    $_POST['titre'], 
                    $_POST['description'] ?? '',
                    $_POST['desc_entree'], 
                    $_POST['desc_plat'], 
                    $_POST['desc_dessert'], 
                    $_POST['prix'], 
                    $_POST['min_personnes'], 
                    $_POST['theme_id'] ?: null, 
                    $_POST['regime_id'] ?: null, 
                    $img1, $img2, $img3, $img4,
                    $_POST['stock'] ?? 50,
                    $_POST['conditions'] ?? null,
                );
            }

            else {
                Menu::update(
                    $_GET['id'], 
                    $_POST['titre'], $_POST['description'], 
                    $_POST['desc_entree'], $_POST['desc_plat'], $_POST['desc_dessert'], 
                    $_POST['prix'], $_POST['min_personnes'], 
                    $_POST['theme_id'] ?: null, $_POST['regime_id'] ?: null, 
                    $img1, $img2, $img3, $img4,
                    $_POST['stock'],
                    $_POST['conditions']
                );
            }

            $redir = ($_SESSION['user']['role_id'] == 2) ? 'employe_dashboard' : 'admin_dashboard';
            header("Location: index.php?page=$redir&success=Menu enregistré");
            exit;
        }
        break;

    case 'admin_menu_delete':
        require_once 'Utils/Auth.php';
        Auth::checkStaff();
        require_once 'Models/Menu.php';
        if (isset($_GET['id'])) Menu::delete($_GET['id']);
        $redir = ($_SESSION['user']['role_id'] == 2) ? 'employe_dashboard' : 'admin_dashboard';
        header("Location: index.php?page=$redir");
        break;

    case 'admin_horaire_update':
        require_once 'Utils/Auth.php';
        Auth::checkStaff();
        require_once 'Models/Horaire.php';
        if(isset($_POST['id'])) {
            Horaire::update($_POST['id'], $_POST['creneau']);
            echo json_encode(['status' => 'success']);
            exit;
        }
        break;

    case 'admin_avis_validate':
    case 'admin_avis_delete':
        require_once 'Utils/Auth.php';
        Auth::checkStaff();
        require_once 'Models/Avis.php';
        if(isset($_GET['id'])) {
            if($page == 'admin_avis_validate') Avis::validate($_GET['id']);
            else Avis::delete($_GET['id']);
            
            if(isset($_GET['ajax'])) { echo json_encode(['status'=>'success']); exit; }
        }
        $redir = ($_SESSION['user']['role_id'] == 2) ? 'employe_dashboard' : 'admin_dashboard';
        header("Location: index.php?page=$redir");
        break;

    case 'employe_dashboard':
        require_once 'Utils/Auth.php';
        Auth::checkStaff();
        
        require_once 'Models/Commande.php';
        require_once 'Models/Menu.php';
        require_once 'Models/Horaire.php';
        require_once 'Models/Avis.php';

        $commandes = Commande::getAll();
        $menus = Menu::getAll();
        $horaires = Horaire::getAll();
        $avisList = Avis::getAllAdmin();

        require_once 'Views/employe/dashboard.php';
        break;

    case 'employe_update_status':
        require_once 'Utils/Auth.php';
        Auth::checkStaff();
        require_once 'Models/Commande.php';
        header('Content-Type: application/json');

        if(isset($_POST['id']) && isset($_POST['statut'])) {
            Commande::updateStatus($_POST['id'], $_POST['statut']);
            
            if ($_POST['statut'] === 'attente_retour_materiel') {
                $cmd = Commande::getById($_POST['id']);
                if ($cmd && !empty($cmd['email'])) {
                    $msg = "Bonjour,\nVous devez rendre le matériel sous 10 jours ou payer 600€ de pénalité.\nCdlt.";
                    @mail($cmd['email'], "Retour Matériel", $msg, "From: contact@viteetgourmand.com");
                }
            }
            echo json_encode(['status' => 'success']);
            exit;
        }
        break;

    case 'employe_cancel_order':
        require_once 'Utils/Auth.php';
        Auth::checkStaff();
        require_once 'Models/Commande.php';
        header('Content-Type: application/json');

        if(isset($_POST['id']) && isset($_POST['motif']) && isset($_POST['contact'])) {
            if(Commande::cancelByEmploye($_POST['id'], $_POST['motif'], $_POST['contact'])) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error']);
            }
            exit;
        }
        break;

    case 'admin_dashboard':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        
        require_once 'Models/Commande.php';
        require_once 'Models/Menu.php';
        require_once 'Models/Avis.php';
        require_once 'Models/User.php';
        require_once 'Models/Contact.php';
        require_once 'Models/Horaire.php';

        $commandes = Commande::getAll();
        $menus = Menu::getAll();
        $avisList = Avis::getAllAdmin();
        $users = User::getAll();
        $messages = Contact::getAll();
        $horaires = Horaire::getAll();

        require_once 'Views/admin/dashboard.php';
        break;

    case 'admin_user_role':
    case 'admin_user_delete':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/User.php';
        
        if($page == 'admin_user_role' && isset($_POST['id'])) {
            
            if ($_POST['role_id'] == 1) {
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Impossible de promouvoir un Admin via le dashboard. Faites-le en base de données.'
                ]);
                exit;
            }

            User::updateRole($_POST['id'], $_POST['role_id']);
            echo json_encode(['status'=>'success']); exit;
        }

        if($page == 'admin_user_delete' && isset($_GET['id'])) {
            User::delete($_GET['id']);
            echo json_encode(['status'=>'success']); exit;
        }
        break;

    case 'admin_message_reply':
    case 'admin_message_delete':
    case 'admin_message_read':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Contact.php';
        header('Content-Type: application/json');

        if ($page == 'admin_message_reply' && isset($_POST['id'])) {
            require_once 'Models/Mailer.php';

            $messageOrigine = Contact::getById($_POST['id']);

            if ($messageOrigine) {
                $sujet = "Réponse à votre message : " . htmlspecialchars($messageOrigine['sujet']);
                $corps = "
                    <html>
                    <body style='font-family: Arial, sans-serif;'>
                        <h3>Bonjour " . htmlspecialchars($messageOrigine['nom_visiteur']) . ",</h3>
                        <p>Merci de nous avoir contactés.</p>
                        <div style='border-left: 3px solid #ccc; padding-left: 10px; margin: 15px 0; color: #555;'>
                            <strong>Votre message :</strong><br>
                            " . nl2br(htmlspecialchars($messageOrigine['contenu_message'])) . "
                        </div>
                        <div style='background-color: #f8f9fa; padding: 15px; border-radius: 5px;'>
                            <strong>Notre réponse :</strong><br>
                            " . nl2br(htmlspecialchars($_POST['message'])) . "
                        </div>
                        <br>
                        <p>Cordialement,<br>L'équipe Vite & Gourmand</p>
                    </body>
                    </html>
                ";

                Mailer::send($messageOrigine['email_visiteur'], $sujet, $corps);
            }

            Contact::saveReply($_POST['id'], $_POST['message']);
            
            echo json_encode(['status'=>'success']); 
            exit;
        }

        if ($page == 'admin_message_delete' && isset($_GET['id'])) {
            Contact::delete($_GET['id']);
            echo json_encode(['status'=>'success']); exit;
        }
        if ($page == 'admin_message_read' && isset($_POST['id'])) {
            Contact::markAsHandled($_POST['id']);
            echo json_encode(['status'=>'success']); exit;
        }
        break;

    case 'admin_stats_filter':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Stats.php';
        $start = $_POST['start'] ?? date('Y-m-01');
        $end = $_POST['end'] ?? date('Y-m-t');
        
        echo json_encode([
            'status' => 'success',
            'ca' => number_format(Stats::getChiffreAffaires($start, $end), 2).' €',
            'best_seller_titre' => Stats::getBestSeller($start, $end)['titre'] ?? 'Aucun',
            'best_seller_count' => (Stats::getBestSeller($start, $end)['total_ventes'] ?? 0).' ventes',
            'history' => Stats::getOrdersByMonth()
        ]);
        exit;
        break;

    case 'admin_commande_delete':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Commande.php';
        if(isset($_GET['id'])) Commande::delete($_GET['id']);
        header('Location: index.php?page=admin_dashboard');
        break;

    case 'admin_commande_status':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/Commande.php';
        if(isset($_POST['id'])) {
            Commande::updateStatus($_POST['id'], $_POST['statut']);
            if(isset($_POST['ajax'])) { echo json_encode(['status'=>'success']); exit; }
        }
        header('Location: index.php?page=admin_dashboard');
        break;

    case 'admin_user_create':
        require_once 'Utils/Auth.php';
        Auth::checkAdmin();
        require_once 'Models/User.php';

        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $pass = $_POST['password'];
            $role = (int)$_POST['role'];

            if ($role === 1) {
                echo json_encode(['status' => 'error', 'message' => 'Interdit de créer un Admin ici.']);
                exit;
            }

            if (User::findByEmail($email)) {
                echo json_encode(['status' => 'error', 'message' => 'Cet email existe déjà.']);
                exit;
            }

            if (User::createByAdmin($nom, $prenom, $email, $pass, $role)) {
                echo json_encode(['status' => 'success']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Erreur SQL.']);
            }
            exit;
        }
        break;

    default:
        http_response_code(404);
        echo "<h1 style='text-align:center; padding-top:100px;'>404 - Page introuvable</h1>";
        break;
}