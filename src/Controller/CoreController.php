<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class CoreController extends AbstractController
{
    protected TranslatorInterface $translator;
    protected EntityManagerInterface $em;

    /**
     * @internal
     * @required
     */
    public function setDefaults(TranslatorInterface $translator, EntityManagerInterface $manager)
    {
        $this->translator = $translator;
        $this->em = $manager;
    }

    /**
     * @return User|object|null
     */
    public function getUser(): ?User
    {
        return parent::getUser();
    }

    /**
     * @return EntityManagerInterface
     */
    public function getDoctrine(): EntityManagerInterface
    {
        return $this->em;
    }

    /**
     * Translates the given message
     * @param string        $id             The message id (may also be an object that can be cast to string)
     * @param array         $parameters     An array of parameters for the message
     * @param string|null   $domain         The domain for the message or null to use the default
     * @param string|null   $locale         The locale or null to use the default
     *
     * @return string                       The translated string
     */
    public function translate(string $id, array $parameters = [], string $domain = null, string $locale = null): string
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}