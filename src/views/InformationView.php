<?php

namespace Views;

use Controllers\InformationController;
use Models\Information;

/**
 * Class InformationView
 *
 * All view for Information (Forms, tables, messages)
 *
 * @package Views
 */
class InformationView extends View
{
    public $carousel;
    
    public function __construct() {
      $this->carousel = new CarouselView();
    }

    /**
     * Display a form to create an information with text
     *
     * @param $title    string
     * @param $content  string
     * @param $endDate  string
     * @param $type     string
     *
     * @return string
     */
    public function displayFormText($title = null, $content = null, $endDate = null, $type = "createText")
    {
        $dateMin = date('Y-m-d', strtotime("+1 day"));
        $form = '
      <form method="post">
        <div class="mb-3">
          <label for="title">Titre <span class="text-muted">(Optionnel)</span></label>
          <input id="info" class="form-control" type="text" name="title" minlength="4" maxlength="40" placeholder="Titre..." value="' . $title . '">
        </div>
        <div class="mb-3">
          <label for="content">Contenu</label>
          <textarea class="form-control" id="content" name="content" rows="3" placeholder="280 caractères au maximum" maxlength="280" minlength="4" required="true">' . $content . '</textarea>
        </div>
        <div class="mb-3">
          <label for="expirationDate">Date d\'expiration</label>
          <input id="expirationDate" class="form-control" type="date" name="expirationDate" min="' . $dateMin . '" value="' . $endDate . '" required="true" />
        </div>
        <button class="btn btn-primary" type="submit" name="' . $type . '">Créer une information</button>';

        if ($type == 'submit') {
            $form .= '<button type="submit" class="btn btn-danger" name="delete" onclick="return confirm(\' Voulez-vous supprimer cette information ?\');">Supprimer</button>';
        }

        return $form . '</form>';
    }

    /**
     * Display a form to create an information with an image
     *
     * @param $title    string
     * @param $content  string
     * @param $endDate  string
     * @param $type     string
     *
     * @return string
     */
    public function displayFormImg($title = null, $content = null, $endDate = null, $type = "createImg")
    {
        $dateMin = date('Y-m-d', strtotime("+1 day"));

        $form = '<form method="post" enctype="multipart/form-data">
					<div class="form-group">
		                <label for="title">Titre <span class="text-muted">(Optionnel)</span></label>
		                <input id="title" class="form-control" type="text" name="title" placeholder="Inserer un titre" maxlength="60" value="' . $title . '">
		            </div>';
        if ($content != null) {
            $form .= '
		       	<figure class="text-center">
				  <img class="img-thumbnail" src="' . TV_UPLOAD_PATH . $content . '" alt="' . $title . '">
				  <figcaption>Image actuelle</figcaption>
				</figure>';
        }
        $form .= '
      <div class="mb-3">
        <label for="contentFile" class="form-label">Ajouter une image</label>
        <input class="form-control" id="contentFile" type="file" name="contentFile">
        <div class="form-text">
          Votre image sera le contenu de l\'information. Elle doit peser moins de 5 MB.
        </div>
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
      </div>
      <div class="mb-3">
        <label for="expirationDate" class="form-label">Date d\'expiration</label>
        <input id="expirationDate" class="form-control" type="date" name="expirationDate" min="' . $dateMin . '" value="' . $endDate . '" required >
      </div>
			<button class="btn btn-primary" type="submit" name="' . $type . '">Créer une information</button>';

        if ($type == 'submit') {
            $form .= '<button type="submit" class="btn btn-danger" name="delete" onclick="return confirm(\' Voulez-vous supprimer cette information ?\');">Supprimer</button>';
        }

        return $form . '</form>';
    }

    /**
     * Display a form to create an information with a table
     *
     * @param null $title
     * @param null $content
     * @param null $endDate
     * @param string $type
     *
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function displayFormTab($title = null, $content = null, $endDate = null, $type = "createTab")
    {
        $dateMin = date('Y-m-d', strtotime("+1 day"));

        $form = '<form method="post" enctype="multipart/form-data">
						<div class="form-group">
			                <label for="title">Titre <span class="text-muted">(Optionnel)</span></label>
			                <input id="title" class="form-control" type="text" name="title" placeholder="Inserer un titre" maxlength="60" value="' . $title . '">
			            </div>';

        if ($content != null) {
            $info = new InformationController();
            $list = $info->readSpreadSheet(TV_UPLOAD_PATH . $content);
            foreach ($list as $table) {
                $form .= $table;
            }
        }

        $form .= '
        <div class="mb-3">
          <label for="contentFile" class="form-label">Ajouter un fichier XLS(X)</label>
          <input class="form-control" id="contentFile" type="file" name="contentFile">
          <div class="form-text">
            Nous vous conseillons de ne pas dépasser trois colonnes, et de ne pas mettre trop de contenu dans une cellule.
          </div>
          <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
        </div>
        <div class="mb-3">
          <label for="expirationDate" class="form-label">Date d\'expiration</label>
          <input id="expirationDate" class="form-control" type="date" name="expirationDate" min="' . $dateMin . '" value="' . $endDate . '" required >
        </div>
			  <button class="btn btn-primary" type="submit" name="' . $type . '">Créer une information</button>';

        if ($type == 'submit') {
            $form .= '<button type="submit" class="btn btn-danger" name="delete" onclick="return confirm(\' Voulez-vous supprimer cette information ?\');">Supprimer</button>';
        }

        return $form . '</form>';
    }

    /**
     * Display a form to create an information with a PDF
     *
     * @param $title    string
     * @param $content  string
     * @param $endDate  string
     * @param $type     string
     *
     * @return string
     */
    public function displayFormPDF($title = null, $content = null, $endDate = null, $type = "createPDF")
    {
        $dateMin = date('Y-m-d', strtotime("+1 day"));

        $form = '<form method="post" enctype="multipart/form-data">
					<div class="form-group">
		                <label for="title">Titre <span class="text-muted">(Optionnel)</span></label>
		                <input id="title" class="form-control" type="text" name="title" placeholder="Inserer un titre" maxlength="60" value="' . $title . '">
		            </div>';

        if ($content != null) {
            $form .= '
			<div class="embed-responsive embed-responsive-16by9">
			  <iframe class="embed-responsive-item" src="' . TV_UPLOAD_PATH . $content . '" allowfullscreen></iframe>
			</div>';
        }

        $form .= '
        <div class="mb-3">
          <label for="contentFile" class="form-label">Ajouter un fichier PDF</label>
          <input class="form-control" id="contentFile" type="file" name="contentFile">
          <div class="form-text">
            Nous vous conseillons de ne pas dépasser trois colonnes, et de ne pas mettre trop de contenu dans une cellule.
          </div>
          <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
        </div>
        <div class="mb-3">
          <label for="expirationDate" class="form-label">Date d\'expiration</label>
          <input id="expirationDate" class="form-control" type="date" name="expirationDate" min="' . $dateMin . '" value="' . $endDate . '" required >
        </div>
			  <button class="btn btn-primary" type="submit" name="' . $type . '">Créer une information</button>';

        if ($type == 'submit') {
            $form .= '<button type="submit" class="btn btn-danger" name="delete" onclick="return confirm(\' Voulez-vous supprimer cette information ?\');">Supprimer</button>';
        }

        return $form . '</form>';
    }

    /**
     * Display a form to create an event information with media or PDFs
     *
     * @param $endDate  string
     * @param $type     string
     *
     * @return string
     */
    public function displayFormEvent($endDate = null, $type = "createEvent")
    {
        $dateMin = date('Y-m-d', strtotime("+1 day"));
        $form = '
		<form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="contentFile" class="form-label">Sélectionner les fichiers</label>
        <input id="contentFile" class="form-control" multiple type="file" name="contentFile[]" />
        <div class="form-text">
          Images ou PDF
        </div>
        <input type="hidden" name="MAX_FILE_SIZE" value="5000000" />
      </div>
      <div class="mb-3">
        <label for="expirationDate" class="form-label">Date d\'expiration</label>
        <input id="expirationDate" class="form-control" type="date" name="expirationDate" min="' . $dateMin . '" value="' . $endDate . '" required >
      </div>
      <button class="btn btn-primary" type="submit" name="' . $type . '">Créer une information</button>';

        if ($type == 'submit') {
            $form .= '<button type="submit" class="btn btn-danger" name="delete" onclick="return confirm(\' Voulez-vous supprimer cette information ?\');">Supprimer</button>';
        }

        $form .= '</form>';
        return $form;
    }

    /**
     * Explain how the information's display
     *
     * @return string
     */
    public function contextCreateInformation()
    {
        return '
    		<div>
    			<h2 style="color: var(--color-secondary) !important;">Les informations</h2>
    			<p class="lead mb-4">
            Lors de la création de votre information, celle-ci est postée directement sur tous les téléviseurs qui utilisent ce site. Les informations que vous créez seront affichées avec les informations déjà présentes.
            Elles seront affichées dans un diaporama défilant les informations une par une sur la partie droite des téléviseurs.
          </p>
    			<div class="text-center">
    				<figure class="figure">
    					<img src="' . URL_PATH . TV_PLUG_PATH . 'public/img/presentation.png" class="figure-img img-fluid rounded" alt="Représentation d\'un téléviseur">
    					<figcaption class="figure-caption">Représentation d\'un téléviseur</figcaption>
    				</figure>
    			</div>
    		</div>';
    }

    /**
     * Display a form to modify an information
     *
     * @param $title
     * @param $content
     * @param $endDate
     * @param $type
     *
     * @return string
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     */
    public function displayModifyInformationForm($title, $content, $endDate, $type)
    {
        if ($type == "text") {
            return '<a href="' . esc_url(get_permalink(get_page_by_title('Gestion des informations'))) . '">< Retour</a>' . $this->displayFormText($title, $content, $endDate, 'submit');
        } elseif ($type == "img") {
            return '<a href="' . esc_url(get_permalink(get_page_by_title('Gestion des informations'))) . '">< Retour</a>' . $this->displayFormImg($title, $content, $endDate, 'submit');
        } elseif ($type == "tab") {
            return '<a href="' . esc_url(get_permalink(get_page_by_title('Gestion des informations'))) . '">< Retour</a>' . $this->displayFormTab($title, $content, $endDate, 'submit');
        } elseif ($type == "pdf") {
            return '<a href="' . esc_url(get_permalink(get_page_by_title('Gestion des informations'))) . '">< Retour</a>' . $this->displayFormPDF($title, $content, $endDate, 'submit');
        } elseif ($type == "event") {
            $extension = explode('.', $content);
            $extension = $extension[1];
            if ($extension == "pdf") {
                return '<a href="' . esc_url(get_permalink(get_page_by_title('Gestion des informations'))) . '">< Retour</a>' . $this->displayFormPDF($title, $content, $endDate, 'submit');
            } else {
                return '<a href="' . esc_url(get_permalink(get_page_by_title('Gestion des informations'))) . '">< Retour</a>' . $this->displayFormImg($title, $content, $endDate, 'submit');
            }
        } else {
            return $this->noInformation();
        }
    }

    /**
     * Display the begin of the slideshow
     */
    public function displayStartSlideshow()
    {
        echo '<img class="iut" src="' . URL_PATH . TV_PLUG_PATH . 'public/img/iut.png" />';
    }

    /**
     * Display the end of the slideshow
     */
    public function displayEndSlideshow()
    {
        echo '';
    }

    /**
     * Display a slide for the slideshow
     *
     * @param $title
     * @param $content
     * @param $type
     * @param bool $adminSite
     */
    public function displaySlide($title, $content, $type, $adminSite = false)
    {
        echo '<div class="myInfoSlides text-center">';

        // If the title is empty
        if ($title != "Sans titre") {
            echo '<h2 class="display-6 fw-bold" style="font-family: var(--bs-font-sans-serif);">' . $title . '</h2>';
        }

        $url = TV_UPLOAD_PATH;
        if ($adminSite) {
            $url = URL_WEBSITE_VIEWER . TV_UPLOAD_PATH;
        }

        if ($type == 'pdf' || $type == "event" || $type == "img") {
            $extension = explode('.', $content);
            $extension = $extension[1];
        }

        if ($type == 'pdf' || $type == "event" && $extension == "pdf") {
            echo '
			<div class="canvas_pdf" id="' . $content . '">
			</div>';
        } elseif ($type == "img" || $type == "event") {
            echo '<img class="img-thumbnail" src="' . $url . $content . '" alt="' . $title . '">';
        } elseif ($type == 'text') {
            echo '<p class="lead">' . $content . '</p>';
        } elseif ($type == 'special') {
            $func = explode('(Do this(function:', $content);
            $text = explode('.', $func[0]);
            foreach ($text as $value) {
                echo '<p class="lead">' . $value . '</p>';
            }
            $func = explode(')end)', $func[1]);
            echo $func[0]();
        } else {
            echo $content;
        }
        echo '</div>';
    }

    public function getHeader($t = 'Gestion des informations', $p = '
    Vous pouvez retrouver ici toutes les informations qui ont été créées sur ce site.
    <br /> <br />
    Les informations sont triées de la plus vieille à la plus récente.
    Vous pouvez modifier une alerte en cliquant sur "Modifier" à la ligne correspondante à l\'l’information.
    Vous souhaitez <b>supprimer une / plusieurs l’information(s)</b> ? Cochez les cases des infos puis cliquez sur "Supprimer" en dessous du tableau.', $i = URL_PATH . TV_PLUG_PATH . 'public/img/info.png')
    {
        return parent::getHeader($t, $p, $i);
    }

    public function noInformation()
    {
        return '
		<a href="' . esc_url(get_permalink(get_page_by_title('Gestion des informations'))) . '">< Retour</a>
		<div>
			<h3>Information non trouvée</h3>
			<p>Cette information n\'existe pas, veuillez bien vérifier d\'avoir bien cliqué sur une information.</p>
			<a href="' . esc_url(get_permalink(get_page_by_title('Créer une information'))) . '">Créer une information</a>
		</div>';
    }

    /**
     * Start the slideshow
     */
    public function displayStartSlideEvent()
    {
        echo '
            <div id="slideshow-container" class="slideshow-container">';
    }

    /**
     * Start a slide
     */
    public function displaySlideBegin()
    {
        echo '
			<div class="mySlides event-slide">';
    }

    /**
     * Display a modal to validate the creation of an information
     */
    public function displayCreateValidate()
    {
        $page = get_page_by_title('Gestion des informations');
        $linkManageInfo = get_permalink($page->ID);
        $this->buildModal('Ajout d\'information validé', '<p class="alert alert-success"> L\'information a été ajoutée </p>', $linkManageInfo);
    }

    /**
     * Display a modal to validate the modification of an information
     * Redirect to manage page
     */
    public function displayModifyValidate()
    {
        $page = get_page_by_title('Gestion des informations');
        $linkManageInfo = get_permalink($page->ID);
        $this->buildModal('Modification d\'information validée', '<p class="alert alert-success"> L\'information a été modifiée </p>', $linkManageInfo);
    }

    /**
     * Display a message if the insertion of the information doesn't work
     */
    public function displayErrorInsertionInfo()
    {
        echo '<p>Il y a eu une erreur durant l\'insertion de l\'information</p>';
    }

    public function informationNotAllowed()
    {
        return '
		<a href="' . esc_url(get_permalink(get_page_by_title('Gestion des informations'))) . '"><Retour</a>
		<div>
			<h3>Vous ne pouvez pas modifier cette alerte</h3>
			<p>Cette information appartient à quelqu\'un d\'autre, vous ne pouvez donc pas modifier cette information.</p>
			<a href="' . esc_url(get_permalink(get_page_by_title('Créer une information'))) . '">Créer une information</a>
		</div>';
    }
}
