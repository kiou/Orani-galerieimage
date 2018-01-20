<?php

namespace DiaporamaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Imagine\Gd\Imagine;
use Imagine\Image\Box;
use GlobalBundle\Service\Upload;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Image
 *
 * @ORM\Table(name="galerieimage_image")
 * @ORM\Entity(repositoryClass="DiaporamaBundle\Repository\ImageRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Image
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetimetz")
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="changed", type="datetimetz", nullable=true)
     */
    private $changed;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\NotBlank(message="Compléter le champ titre")
     */
    private $titre;

    /**
     * @Assert\Image(
        minWidth = 640,
        minHeight = 480,
        mimeTypes = {"image/jpeg", "image/png"}),
        maxSize = "3M"
     */
    private $fileimage;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var bool
     *
     * @ORM\Column(name="isActive", type="boolean")
     */
    private $isActive;

    /**
     * @var int
     *
     * @ORM\Column(name="poid", type="integer")
     */
    private $poid;

    /**
     * @ORM\ManyToOne(targetEntity="DiaporamaBundle\Entity\Galerie", inversedBy="images")
     */
    private $galerie;

    public function __construct()
    {
        $this->isActive = true;
        $this->created = new \DateTime();
        $this->poid = 1;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return Image
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set changed
     *
     * @param \DateTime $changed
     *
     * @return Image
     */
    public function setChanged($changed)
    {
        $this->changed = $changed;

        return $this;
    }

    /**
     * Get changed
     *
     * @return \DateTime
     */
    public function getChanged()
    {
        return $this->changed;
    }

    /**
     * @ORM\PreUpdate()
     */
    public function preChanged()
    {
        $this->changed = new \DateTime();
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Image
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return Galerie
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getFileimage()
    {
        return $this->fileimage;
    }

    public function setFileimage(UploadedFile $fileimage = null)
    {
        $this->fileimage = $fileimage;
        if (null !== $this->image){
            $this->image = null;
        }
    }

    public function uploadImage()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif), on ne fait rien
        if (null === $this->fileimage) {
            return;
        }

        $upload = new Upload();
        $this->image = $upload->createName(
            $this->fileimage->getClientOriginalName(),
            $this->getUploadRootDir().'/'
        );

        $imagine = new Imagine();

        /* Tmp */
        $size = new Box(1920,1080);
        $imagine->open($this->fileimage)
                ->thumbnail($size, 'inset')
                ->save($this->getUploadRootDir().'tmp/'.$this->image);

        /* Miniature */
        $size = new Box(300,300);
        $imagine->open($this->fileimage)
                ->thumbnail($size, 'outbound')
                ->save($this->getUploadRootDir().'miniature/'.$this->image);

    }

    public function getUploadRootDir()
    {
        return __DIR__.'/../../../web/img/galerie/';
    }

    /**
     * @Assert\Callback
     */
    public function isFileimageValid(ExecutionContextInterface $context)
    {

        if(empty($this->id)){
            if(empty($this->fileimage)) $context->buildViolation('Complétez le champ image')->atPath('fileimage')->addViolation();
        }

    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Image
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Retourne 1 si actif 0 si pas actif
     */
    public function reverseState()
    {
        $etat = $this->getIsActive();

        return !$etat;
    }

    /**
     * Set poid
     *
     * @param integer $poid
     *
     * @return Image
     */
    public function setPoid($poid)
    {
        $this->poid = $poid;

        return $this;
    }

    /**
     * Get poid
     *
     * @return integer
     */
    public function getPoid()
    {
        return $this->poid;
    }

    /**
     * Set galerie
     *
     * @param \DiaporamaBundle\Entity\Galerie $galerie
     *
     * @return Image
     */
    public function setGalerie(\DiaporamaBundle\Entity\Galerie $galerie = null)
    {
        $this->galerie = $galerie;

        return $this;
    }

    /**
     * Get galerie
     *
     * @return \DiaporamaBundle\Entity\Galerie
     */
    public function getGalerie()
    {
        return $this->galerie;
    }
}
