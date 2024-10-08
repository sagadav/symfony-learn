<?php
namespace App\Controller;

use App\DTO\PostDto;
use App\Entity\Post;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/posts')]
class PostController extends AbstractController
{

    #[Route('/', methods: ['GET'])]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findAll();
        return $this->json(['content' => $posts]);
    }

    #[Route('/create', methods: ['POST'])]
    public function create(Request $request, PostRepository $postRepository, EntityManagerInterface $em,
                           #[MapRequestPayload] PostDto $postDto): Response
    {
//        $data = json_decode($request->getContent(), true);

//        if (!isset($data['title']) || !isset($data['content'])) {
//            return $this->json(['error' => 'Invalid data'], Response::HTTP_BAD_REQUEST);
//        }

        $post = (new Post())
            ->setTitle($postDto->title)
            ->setContent($postDto->content);
        $em->persist($post);
        $em->flush();

        return $this->json(['message' => 'Post created successfully', 'post' => $post], Response::HTTP_CREATED);
    }
}