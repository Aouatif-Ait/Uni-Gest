<?PHP 

class UtilisateurController{

    private $UtilisateurModel;

    public function __construct($UtilisateurModel){
        $this->UtilisateurModel = $UtilisateurModel;
    }

    public function ajouterUtilisateur(){
        $nom = '';
        $prenom = '';
        $email = '';
        $mtpasse = '';
        $statut = '';
        $role = '';
        $erreurs = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->UtilisateurModel->ajouterUtilisateur($_POST);
            if ($result === true) {
                $_SESSION['message'] = "L'utilisateur a été ajouté avec succès.";
                header('Location: ' . BASE_PATH_SERVER . '/index.php/liste_utilisateurs/');
                exit;
            } else {
                $erreurs = $result;
                $nom    = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $email     = $_POST['email'];
                $mtpasse    = $_POST['mtpasse'];
                $statut = $_POST['statut'];
                $role     = $_POST['role'];
            }
        }
        include VIEW_PATH . '/user/ajouter_utilisateur.php';
    }

    public function listerUtilisateur(){
        $role = isset($_GET['role']) ? $_GET['role'] : 'Etudiant';
        $utilisateurs = $this->UtilisateurModel->getListeUtilisateur($role);
        $erreurs = [];
        if (isset($utilisateurs['bdd'])) {
            $erreurs['bdd'] = $utilisateurs['bdd'];
            $utilisateurs = [];
        }
        // Le fichier s'appelle listeUtilisateur.php
        include VIEW_PATH . '/user/listeUtilisateur.php';
    }

    public function modifierUtilisateur(int $IDUSER){
        $nom = '';
        $prenom = '';
        $email = '';
        $mtpasse = '';
        $statut = '';
        $role = '';
        $erreurs = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->UtilisateurModel->modifierUtilisateur($_POST, $IDUSER);
            if ($result === true) {
                $_SESSION['message'] = "L'utilisateur a été modifié avec succès.";
                header('Location: ' . BASE_PATH_SERVER . '/index.php/liste_utilisateurs/');
                exit;
            } else {
                $erreurs = $result;
                $nom    = $_POST['nom'];
                $prenom = $_POST['prenom'];
                $email     = $_POST['email'];
                $mtpasse    = $_POST['mtpasse'];
                $statut = $_POST['statut'];
                $role     = $_POST['role'];
            }
        } else {
            $utilisateur = $this->UtilisateurModel->getUtilisateur($IDUSER);
            if (!$utilisateur || isset($utilisateur['bdd'])) {
                $_SESSION['error'] = isset($utilisateur['bdd'])
                    ? "Erreur BDD : " . $utilisateur['bdd']
                    : "Utilisateur introuvable.";
                header('Location: ' . BASE_PATH_SERVER . '/index.php/liste_utilisateurs/');
                exit;
            }
            $nom    = $utilisateur['nom'];
            $prenom = $utilisateur['prenom'];
            $email = $utilisateur['email'];
            $statut = $utilisateur['statut'];
            $role  = $utilisateur['role'];
            // password : on ne pré-remplit jamais le champ mot de passe
        }
        include VIEW_PATH . '/user/modifier_utilisateur.php';
    }

    public function supprimerUtlisateur(int $IDUSER){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $resultat = $this->UtilisateurModel->supprimerUtilisateur($IDUSER);
            if ($resultat === true) {
                $_SESSION['message'] = "L'utilisateur a été supprimé avec succès.";
            } else {
                if (isset($resultat['IDUSER'])) {
                    $_SESSION['error'] = $resultat['IDUSER'];
                } elseif (isset($resultat['bdd'])) {
                    $_SESSION['error'] = "Erreur de base de données : " . $resultat['bdd'];
                } else {
                    $_SESSION['error'] = "Erreur lors de la suppression de l'utilisateur.";
                }
            }
            header('Location: ' . BASE_PATH_SERVER . '/index.php/liste_utilisateurs/');
            exit;
        }
        $utilisateur = $this->UtilisateurModel->getUtilisateur($IDUSER);
        $erreurs = [];
        if (isset($utilisateur['bdd'])) {
            $erreurs['bdd'] = $utilisateur['bdd'];
            $utilisateur = [];
        }
        include VIEW_PATH . '/user/supprimer_utilisateur.php';
    }

}
?>
