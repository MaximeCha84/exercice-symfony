<?php
namespace App\Service;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Serializer\SerializerInterface;

class AgeService {

    public function calculateAge(User $user){
        
        $now = new DateTimeImmutable();
        
        $age = $now->diff($user->getBirthDate(), true)->y;

        return $age;
        
    }
}