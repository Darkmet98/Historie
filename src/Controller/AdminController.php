<?php


namespace App\Controller;


use App\Entity\PoFile;
use App\Entity\Projects;
use App\Entity\User;
use Cz\Git\GitRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Gettext\Generator\JsonGenerator;
use Gettext\Loader\PoLoader;
use Symfony\Component\Security\Core\Encoder\EncoderFactory;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use RecursiveDirectoryIterator as dirIterator;
use RecursiveIteratorIterator as recursiveIterator;

class AdminController extends EasyAdminController
{

    /**
     * @param $path
     * @return array
     *
     * Search the po files on the directory
     */
    function searchPo(string $path) {
        $arr = [];

        $it = new dirIterator($path);
        $display = Array ( 'po' );
        foreach(new recursiveIterator($it) as $file)
        {
            $array = explode('.', $file);
            if (in_array(strtolower(array_pop($array)), $display))
                array_push($arr, $file);
        }
        return $arr;
    }

    //User Entity
    protected function persistUserEntity(User $user)
    {
        $encodedPassword = $this->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        parent::persistEntity($user);
    }

    protected function updateUserEntity(User $user)
    {
        $encodedPassword = $this->encodePassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        parent::updateEntity($user);
    }

    private function encodePassword(User $user, $password)
    {
        $passwordEncoderFactory = new EncoderFactory([
            User::class => new MessageDigestPasswordEncoder('sha512', true, 5000)
        ]);

        $encoder = $passwordEncoderFactory->getEncoder($user);

        return $encoder->encodePassword($password, $user->getSalt());
    }
}