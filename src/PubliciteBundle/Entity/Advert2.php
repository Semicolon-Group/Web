<?php

namespace PubliciteBundle\Entity;

use BaseBundle\BaseBundle;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


class Advert2 extends \BaseBundle\Entity\Advert
{
    /**
     * @Assert\File {maxSize="500k"}
     */
   public $file;
    public function getWebPath()
    { return null === $this->getPhotoUrl() ? null : $this->getUploadDir.'/'.$this->getPhotoUrl(); }
    protected function getUploadRootDir()
    {
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }
    protected function getUploadDir()
    {
        return 'images';

    }
    public function uploadPic()
    {
        $this->file->move($this->getUploadRootDir(),$this->file->getClientOriginalName());
        $this->getPhotoUrl()==$this->file->getClientOriginalName();
        $this->file=null;
    }
}

