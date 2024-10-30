<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ClientSearchDto
{
    // /**
    //  * @Assert\Regex(
    //  *     pattern="/^(77|78|76)[0-9]{7}$/",
    //  *     message="Le numéro de téléphone doit commencer par 77, 78 ou 76 suivi de 7 chiffres."
    //  * )
    //  */
    public string $telephon;

    public string $surname;

    public function __construct()
    {
        $this->telephon = '';
        $this->surname = '';
    }
}