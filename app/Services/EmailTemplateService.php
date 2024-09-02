<?php

namespace App\Services;

use App\Models\EmailTemplate;

class EmailTemplateService
{
    public function getTemplate($name, $placeholders = [])
    {
        $template = EmailTemplate::where('name', $name)->first();

        if (!$template) {
            // throw new \Exception("Email template not found.");
            return 0;
        }

        $subject = $this->replacePlaceholders($template->subject, $placeholders);
        $body = $this->replacePlaceholders($template->body, $placeholders);

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }

    protected function replacePlaceholders($content, $placeholders)
    {
        foreach ($placeholders as $key => $value) {
            $content = str_replace("[$key]", $value, $content);
        }

        return $content;
    }
}
