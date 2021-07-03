<?php


namespace App\Controller;

use App\Entity\Personne;
use App\Repository\PersonneRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class PersonneController extends AbstractController
{
    private $maxAge = 150;

    /**
     * @Route("/personne/{id}", requirements={"id":"\d+"}, name="show_personne", methods={"GET"})
     */
    public function showPersonne(Personne $personne): JsonResponse
    {
        return $this->json($personne);
    }

    /**
     * @Route("/personne", name="add_personne", methods={"POST"})
     * @throws Exception
     */
    public function addPersonne(Request $request, EntityManagerInterface $em): JsonResponse
    {
        if (!$request->request->has('nom') || !$request->request->has('prenom') || !$request->request->has('date_naissance')) {
            return $this->json(
                [
                    'message' => 'Error missing argument(s)',
                ],
                400
            );
        }
        if (!$this->checkDate($request->request->get('date_naissance'))) {
            return $this->json(
                [
                    'message' => 'Error max age',
                ],
                400
            );
        }
        $personne = new Personne();
        $personne
            ->setNom($request->request->get('nom'))
            ->setPrenom($request->request->get('prenom'))
            ->setDateNaissance(new DateTime($request->request->get('date_naissance')))
        ;
        $em->persist($personne);

        $em->flush();
        return $this->json([
            'message' => 'Personne created',
            'id' => $personne->getId(),
        ]);
    }

    /**
     * @Route("/personne/{id}", requirements={"id":"\d+"}, name="edit_personne", methods={"PATCH"})
     * @throws Exception
     */
    public function editPersonne(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        /** @var PersonneRepository $personneRep */
        $personneRep = $em->getRepository(Personne::class);

        $personne = $personneRep->find($id);
        if ($personne === null) {
            return $this->json(
                [
                    'message' => 'Error Personne not found',
                ],
                400
            );
        }

        if ($request->query->has('nom')) {
            $personne->setNom($request->query->get('nom'));
        }

        if ($request->query->has('prenom')) {
            $personne->setPrenom($request->query->get('prenom'));
        }

        if ($request->query->has('date_naissance')) {
            if (!$this->checkDate($request->query->get('date_naissance'))) {
                return $this->json(
                    [
                        'message' => 'Error max age',
                    ],
                    400
                );
            }
            $personne->setDateNaissance(new DateTime($request->query->get('date_naissance')));
        }
        $em->persist($personne);
        $em->flush();

        return $this->json([
            'message' => 'Personne updated',
            'personne' => $personne,
        ]);
    }

    /**
     * @throws Exception
     */
    private function checkDate(string $testDate): bool
    {
        $date1 = new DateTime("now");
        $date2 = new DateTime($testDate);
        $diff = $date2->diff($date1)->y;
        return $diff < $this->maxAge;
    }
}