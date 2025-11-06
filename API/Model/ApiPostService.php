<?php
use GuzzleHttp\Client;

class ApiPostService
{
    private Client $client;
    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'https://jsonplaceholder.typicode.com/']);
    }

    public function getPosts(): array
    {
        try {
            $res = $this->client->get('posts', ['timeout' => 5]);
            if ($res->getStatusCode() !== 200) return [];
            return json_decode($res->getBody()->getContents(), true) ?: [];
        } catch (\Exception $e) {
            // loguear $e->getMessage()
            return [];
        }
    }

    public function getPost(int $id): ?array
    {
        try {
            $res = $this->client->get("posts/{$id}");
            if ($res->getStatusCode() !== 200) return null;
            return json_decode($res->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function createPost(array $data): ?array
    {
        // validar $data aquí o lanzar excepción
        $res = $this->client->post('posts', ['json' => $data]);
        return json_decode($res->getBody()->getContents(), true);
    }

    public function updatePost(int $id, array $data): ?array
    {
        $res = $this->client->put("posts/{$id}", ['json' => $data]);
        return json_decode($res->getBody()->getContents(), true);
    }

    public function deletePost(int $id): bool
    {
        $res = $this->client->delete("posts/{$id}");
        return in_array($res->getStatusCode(), [200, 204]);
    }
}
