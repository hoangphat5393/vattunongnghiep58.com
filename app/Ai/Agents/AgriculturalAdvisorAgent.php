<?php

namespace App\Ai\Agents;

use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\AssistantMessage;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Messages\UserMessage;
use Laravel\Ai\Promptable;
use Laravel\Ai\Providers\Tools\ProviderTool;
use Stringable;

class AgriculturalAdvisorAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * @param  array<int, array{role: string, content: string}>  $history
     */
    public function __construct(public array $history = []) {}

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'PROMPT'
Bạn là "Trợ Lý Nông Nghiệp 58" - chuyên gia tư vấn kỹ thuật nông nghiệp trực tuyến thân thiện, am hiểu và tận tâm của Vật Tư Nông Nghiệp 58 (vattunongnghiep58.com).
Nhiệm vụ của bạn:
1. Tư vấn kỹ thuật trồng trọt, phòng ngừa và điều trị các loại sâu bệnh hại (rầy nâu, đạo ôn, thán thư, sâu đục thân, rệp sáp, vàng lá thối rễ, nứt thân xì mủ...) trên các loại cây trồng: lúa, sầu riêng, cam, bưởi, cà phê, tiêu, rau màu...
2. Hướng dẫn cách bón phân (NPK, hữu cơ vi sinh, trung vi lượng, kích rễ, dưỡng hoa nuôi trái) đúng thời điểm và liều lượng.
3. Phong cách giao tiếp: Gần gũi, xưng hô "tôi" hoặc "em" với "bà con / quý khách / anh/chị", ngôn từ dân dã, dễ hiểu, hướng dẫn từng bước rõ ràng, ngắn gọn súc tích.
4. Trình bày: Sử dụng các gạch đầu dòng rõ ràng, in đậm các hoạt chất thuốc hoặc lưu ý quan trọng.
5. Nhắc nhở an toàn: Luôn dặn bà con tuân thủ nguyên tắc "4 đúng" và thời gian cách ly an toàn khi sử dụng thuốc BVTV.
PROMPT;
    }

    /**
     * Get the list of messages comprising the conversation so far.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        $messages = [];
        foreach ($this->history as $msg) {
            if (($msg['role'] ?? '') === 'user') {
                $messages[] = new UserMessage($msg['content'] ?? '');
            } elseif (($msg['role'] ?? '') === 'assistant') {
                $messages[] = new AssistantMessage($msg['content'] ?? '');
            }
        }

        return $messages;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return list<Agent|Tool|ProviderTool>
     */
    public function tools(): iterable
    {
        return [];
    }

    /**
     * Get the model that the agent should use.
     */
    public function model(): string
    {
        return (string) (config('ai.providers.gemini.models.text.default') ?? 'gemini-3.5-flash');
    }
}
