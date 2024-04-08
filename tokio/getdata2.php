<?php

        $queryText = "Hello";
        // Read JSON data from Dialogflow
        $request = file_get_contents('php://input');
        $input = json_decode($request, true);
        
        // // Get the query text
        // $queryText = $input['queryResult']['queryText'];
        
        // Prepare data for OpenAI API
        $data = [
            "model" => "ft:gpt-3.5-turbo-0125:personal::8xe2NuPn",
            "messages" => [
                ["role" => "system", "content" => "You are an intelligent insurance agent expert. Assuming the persona of a woman named Yuki."],
                ["role" => "assistant", "content" => "เบอร์ติดต่อบริษัทโตเกียวมารีน"],
                ["role" => "system", "content" => "ติดต่อสอบถามข้อมูลได้ที่ 02-619-1400"], 
                ["role" => "user", "content" => $queryText]
            ],
            "temperature" => 0,
            "max_tokens" => 1000
        ];

        echo "<pre>" . print_r($data, true) . "</pre>";
        



?>
