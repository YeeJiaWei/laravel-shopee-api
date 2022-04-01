<?php


namespace Yeejiawei\LaravelShopeeApi\NodeTwo;


use Yeejiawei\LaravelShopeeApi\NodeTwo;

class Chat extends NodeTwo
{
    public function getNodePrefix(): string
    {
        return '/sellerchat';
    }

    public function getMessage(int $conversation_id, array $parameters = [])
    {
        return $this->get('/get_message', array_merge($parameters, ['conversation_id' => $conversation_id]));
    }

    public function sendMessage(int $to_id, array $parameters = [])
    {
        return $this->post('/send_message', array_merge($parameters, [
            'to_id' => $to_id,
        ]));
    }

    public function sendAutoreplyMessage(int $to_id, string $message)
    {
        return $this->post('/send_autoreply_message', [
            'to_id' => $to_id,
            'content' => [
                'text' => $message,
            ],
        ]);
    }

    /**
     * @param array $parameters
     * @param string $direction value: latest, older
     * @param string $type value: all, pinned, unread
     * @throws \Exception
     */
    public function getConversations(array $parameters = [], string $direction = 'latest', string $type = 'all')
    {
        return $this->get('/get_conversation_list', array_merge($parameters, [
            'direction' => $direction,
            'type' => $type,
        ]));
    }

    /**
     * @param int $conversation_id
     * @throws \Exception
     */
    public function getConversation(int $conversation_id)
    {
        return $this->get('/get_one_conversation', ['conversation_id' => $conversation_id]);
    }

    public function deleteConversation(int $conversation_id)
    {
        return $this->post('/delete_conversation', ['conversation_id' => $conversation_id]);
    }

    public function getUnreadConversationCount()
    {
        return $this->post('/get_unread_conversation_count');
    }

    public function pinConversation(int $conversation_id)
    {
        return $this->post('/pin_conversation', ['conversation_id' => $conversation_id]);
    }

    public function unpinConversation(int $conversation_id)
    {
        return $this->post('/unpin_conversation', ['conversation_id' => $conversation_id]);
    }

    public function readConversationn(int $conversation_id, string $last_read_message_id)
    {
        return $this->post('/read_conversation', [
            'conversation_id' => $conversation_id,
            'last_read_message_id' => $last_read_message_id,
        ]);
    }

    public function unreadConversation(int $conversation_id)
    {
        return $this->post('/unread_conversation', ['conversation_id' => $conversation_id]);
    }

    public function getOfferToggleStatus()
    {
        return $this->post('/get_offer_toggle_status');
    }

    public function setOfferToggleStatus(string $make_offer_status)
    {
        return $this->post('/set_offer_toggle_status', ['make_offer_status' => $make_offer_status]);
    }

    public function uploadImage($file)
    {
        return $this->post('/upload_image', ['file' => $file]);
    }

}
