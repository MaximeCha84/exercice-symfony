<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeInterface;
use App\Entity\Property;
use App\Service\AgeService;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashBoardController extends AbstractController
{
    #[Route('/', name: 'app_dash_board')]
    public function index(EntityManagerInterface $manager, SerializerInterface $serializer, AgeService $ageService): Response
    {
        $users = $manager->getRepository(User::class)->findAll();
        
        // dump(count($users));

        $newUserArray = array();

        foreach ($users as $user){
            
            $userHandle = json_decode($serializer->serialize($user,'json',['groups'=>'user:read']));
            $userHandle->age = json_encode($ageService->calculateAge($user));
            $newUserArray[] = $userHandle;
            
        }

        $newUserArray =  $serializer->serialize($newUserArray,'json');

        // $usersList = $serializer->serialize($users,'json',['groups'=>'user:read']);

        return $this->render('users/userDashboard.html.twig', ['usersList' => $newUserArray]);
    }

    #[Route('/user/{id}/properties', name:'app_properties_list')]
    public function getProperties(EntityManagerInterface $manager, User $user=null){

        if(!$user){
            dd("Utilisateur introuvable");
        } else {
            $properties = $manager->getRepository(Property::class)->findBy(['owner'=> $user]);
        }

        return $this->render('properties/propertiesDashboard.html.twig', [
            'user'=> $user,
            'properties' => $properties
        ]);
    }

        #[Route('/delete/user/{id}', methods:'DELETE')]
    public function deleteUser(EntityManagerInterface $manager, User $user){

        $manager->getRepository(User::class)->remove($user,true);

        return $this->redirectToRoute('app_dash_board');
    }

    #[Route('/add/user', methods:['POST'])]
    public function addUser(Request $request, Response $response = null, User $newUser = null, EntityManagerInterface $manager){

        $response = new Response();

        //On récupère les données et on les transforme en tableau PHP
        $content = $request->getContent();
        $data = json_decode($content, true);

        $newUser = new User();
        $newUser->setLastName($data['lastName']);
        $newUser->setFirstName($data['firstName']);
        $newUser->setEmail($data['email']);
        $newUser->setAdress($data['address']);
        $newUser->setPhoneNumber($data['phoneNumber']);
        $newUser->setBirthDate(new DateTimeImmutable($data['birthDate']));
        
        $manager->getRepository(User::class)->save($newUser,true);

       
        if( $manager->getRepository(User::class)->findBy([
            "firstName"=>$newUser->getFirstName(),
            "lastName"=>$newUser->getLastName(),
            "birthDate"=>$newUser->getBirthDate(),
            "email"=>$newUser->getEmail()
            ])){
                $response->setStatusCode(200, "Utilisateur créé avec succès");
                $response->headers->set('Content-Type', "application/json");
                $response->setContent("Utilisateur créé avec succès");
            }else {
                $response->setStatusCode(500, "Problème avec la création d'un utilisateur");
                $response->headers->set('Content-Type', "application/json");
                $response->setContent("Utilisateur non créé");
            }

        
     
       
        // $manager->getRepository(User::class)->save($user,true);
        

        // return $this->render('users/userDashboard.html.twig');
        return $response;
    }

}
