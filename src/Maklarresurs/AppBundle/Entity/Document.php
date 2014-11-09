<?php

namespace Maklarresurs\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 *
 * @ORM\Table()
 * @ORM\HasLifecycleCallbacks
 * @ORM\Entity
 */
class Document
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Maklarresurs\UserBundle\Entity\User")
     */
    protected $user;


    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="Lappning")
     */
    protected  $lappning;

    /**
     * @var string $image
     * @Assert\File( maxSize = "10024k", mimeTypesMessage = "Please upload a valid File")
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLappning()
    {
        return $this->lappning;
    }

    /**
     * @param mixed $lappning
     */
    public function setLappning($lappning)
    {
        $this->lappning = $lappning;
    }


    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }



    /**
     * Set image
     *
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
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

    public function getFullImagePath() {
        return null === $this->image ? null : $this->getUploadRootDir(). $this->image;
    }

    protected function getUploadRootDir() {
        // the absolute directory path where uploaded campaigns should be saved
        return $this->getTmpUploadRootDir().$this->getId()."/";
    }

    protected function getTmpUploadRootDir() {
        // the absolute directory path where uploaded campaigns should be saved
        return __DIR__ . '/../../../../web/uploads/';
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PostUpdate()
     */
    public function uploadImage() {
        // the file property can be empty if the field is not required
        if (null === $this->image) {
            return;
        }
        if(!$this->id){
            $this->image->move($this->getTmpUploadRootDir(), $this->image->getClientOriginalName());
        }else{
            $this->image->move($this->getUploadRootDir(), $this->image->getClientOriginalName());
        }
        $this->setImage($this->image->getClientOriginalName());
    }

    /**
     * @ORM\PostPersist()
     */
    public function moveImage()
    {
        if (null === $this->image) {
            return;
        }
        if(!is_dir($this->getUploadRootDir())){
            mkdir($this->getUploadRootDir());
        }
        copy($this->getTmpUploadRootDir().$this->image, $this->getFullImagePath());
        unlink($this->getTmpUploadRootDir().$this->image);
    }

    /**
     * @ORM\PreRemove()
     */
    public function removeImage()
    {
        unlink($this->getFullImagePath());
        //rmdir($this->getUploadRootDir());
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeFile($file)
    {
        $file_path = $this->getUploadRootDir().$this->getId();
        if(file_exists($file_path)) unlink($file_path);
    }
}