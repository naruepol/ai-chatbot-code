<?php
$data = [
            "model" => "ft:gpt-3.5-turbo-0125:personal::8xe2NuPn",
            "messages" => [
                ["role" => "assistant", "content" => "assistant_content"],
                ["role" => "system", "content" => "system_content"],
            ],
            "temperature" => 0,
            "max_tokens" => 1000
        ];

print_r($data);