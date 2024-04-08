<?php
    $api_key = "sk-";
    
    class Chatbot2 {
        private $api_key;
        
        public function __construct($api_key) {
            $this->api_key = $api_key;
        }
        
        public function processRequest($result_recommend) {
        
            // Get the query text
            $queryText = $result_recommend;
            
            // Prepare data for OpenAI API
            $data = [
                "model" => "",
                "messages" => [
                    ["role" => "system", "content" => "You are an intelligent insurance agent expert. Assuming the persona of a woman named Yuna, you are a virtual assistant who can answer questions about Tokio Marine Life Insurance Thailand. You can provide information about the company, its products, and its services. You can also help users with their insurance needs, such as finding the right policy, making a claim, or getting support. You can also provide general information about insurance and financial planning. You are knowledgeable, helpful, and friendly. You are here to help users get the information they need and make the right decisions about their insurance."],
                    ["role" => "assistant", "content" => "1 คือ"],
                    ["role" => "system", "content" => "ประกัน CI Care"],
                    ["role" => "assistant", "content" => "2 คือ"],
                    ["role" => "system", "content" => "ประกัน Early CI Care"],
                    ["role" => "assistant", "content" => "3 คือ"],
                    ["role" => "system", "content" => "ประกัน Female Special Diease"],
                    ["role" => "assistant", "content" => "4 คือ"],
                    ["role" => "system", "content" => "ประกัน HB Super Plan"],
                    ["role" => "assistant", "content" => "5 คือ"],
                    ["role" => "system", "content" => "ประกัน OPD"],
                    ["role" => "assistant", "content" => "6 คือ"],
                    ["role" => "system", "content" => "ประกัน Tokio Cancer Care"],
                    ["role" => "assistant", "content" => "7 คือ"],
                    ["role" => "system", "content" => "ประกัน Tokio Good Health"],
                    ["role" => "user", "content" => "อธิบายเกี่ยวกับประกัน โดยบอกเฉพาะชื่อของประกันที่เป็นภาษาอังกฤษไม่ต้องให้คำอธิบาย".$queryText]                    
                ],
                "temperature" => 0,
                "max_tokens" => 1000
            ];
            
            // Send request to OpenAI API
            $result = $this->sendRequest($data);
            
            // Convert JSON response to PHP array
            $jsonArray = json_decode($result, true);
            
            // Get the content from the response
            $content = $jsonArray['choices'][0]['message']['content'];
            
            // echo "<br>";
            // echo "ประกันที่แนะนำจากข้อมูลที่ได้รับจากลูกค้า";
            // echo "<br>";
            // echo $content;
            $cleanedText = str_replace("\n", ", ", $content);
            $cleanedText2 = "Introducing the following insurance plans: ".$result_recommend.".,"." The details are as follows: ".$cleanedText.", website : www.tokiomarine.com";
            // สร้างข้อมูลในรูปแบบของ $response
            $response = [
                'fulfillmentText' => json_encode($cleanedText2)
            ];

            // แสดงผลลัพธ์
            echo json_encode($response);              
        }
        
        private function sendRequest($data) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->api_key
            ));
            
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            
            return $result;
        }
    }

    class Chatbot {
        private $api_key;
        
        public function __construct($api_key) {
            $this->api_key = $api_key;
        }
        
        public function processRequest() {
                $host = 'localhost'; // หรือ IP ของเซิร์ฟเวอร์ฐานข้อมูล
                $dbname = 'tokio';
                $user = 'root';
                $password = '';
                $port = 3307;

                // Read JSON data from Dialogflow
                $request = file_get_contents('php://input');
                $input = json_decode($request, true);
                
                // Get the query text
                $queryText = $input['queryResult']['queryText'];
            try {   
                $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port", $user, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stmt = $pdo->query("SELECT assistant_content, system_content FROM messages WHERE status = 'active'");

                $data = [
                    "model" => "ft:gpt-3.5-turbo-0125:personal::8xe2NuPn",
                    "messages" => [
                        // ["role" => "system", "content" => "You are an intelligent insurance agent expert. Assuming the persona of a woman named Akira."],
                        // ["role" => "assistant", "content" => "เบอร์ติดต่อบริษัทโตเกียวมารีน"],
                        // ["role" => "system", "content" => "ติดต่อสอบถามข้อมูลได้ที่ 02-619-1400"],
                        // ["role" => "assistant", "content" => "ค่าใช้จ่ายในการสอบ IC License เท่าไหร่ ?"],
                        // ["role" => "system", "content" => "ค่าสมัครสอบเพื่อขอใบอนุญาตตัวแทนครั้งละ 1300 บาท"]
                        // ["role" => "user", "content" => $queryText]
                    ],
                    "temperature" => 0,
                    "max_tokens" => 1000
                ];
                $data["messages"][] = ["role" => "system", "content" => "You are an intelligent insurance agent expert. Assuming the persona of a woman named Yuna, you are a virtual assistant who can answer questions about Tokio Marine Life Insurance Thailand. You can provide information about the company, its products, and its services. You can also help users with their insurance needs, such as finding the right policy, making a claim, or getting support. You can also provide general information about insurance and financial planning. You are knowledgeable, helpful, and friendly. You are here to help users get the information they need and make the right decisions about their insurance."];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    // ดึงข้อมูลและเพิ่มเข้าในอาร์เรย์ 'messages'
                    $data["messages"][] = ["role" => "assistant", "content" => $row['assistant_content']];
                    $data["messages"][] = ["role" => "system", "content" => $row['system_content']];
                }
                $data["messages"][] = ["role" => "user", "content" => $queryText];
                
                // Send request to OpenAI API
                $result = $this->sendRequest($data);
                
                // Convert JSON response to PHP array
                $jsonArray = json_decode($result, true);
                
                // Get the content from the response
                $content = $jsonArray['choices'][0]['message']['content'];
                
                // Prepare response data
                $response = [
                    "fulfillmentText" => $content
                ];
                
                // Convert array to JSON and send response
                echo json_encode($response);
            } catch(PDOException $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        
        private function sendRequest($data) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->api_key
            ));
            
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }
            curl_close($ch);
            
            return $result;
        }
    }
 
    
    // รับ JSON จาก Dialogflow
    $json = file_get_contents('php://input');
    $request = json_decode($json, true);
    $tmpScoredLabelsString = "";
    // ตรวจสอบว่ามี outputContexts ใน JSON หรือไม่
    if (isset($request['queryResult']['outputContexts'])) {
        $outputContexts = $request['queryResult']['outputContexts'];
        // $numberOfElements = count($outputContexts);
        // $count_data = "There are $numberOfElements elements in the array.";
        // วนลูปผ่าน outputContexts เพื่อค้นหาข้อมูล  
        foreach ($outputContexts as $context) {
            // ตรวจสอบว่ามีชื่อ context เป็น insurance_agent-custom-followup หรือไม่
            if (isset($context['name']) && strpos($context['name'], 'insurance_agent-custom-followup') !== false) {  
                // เข้าถึงค่าของ จาก parameters ใน context
                $f_age = $context['parameters']['f_age'];
                $f_gender = $context['parameters']['f_gender'];
                $f_occupation = $context['parameters']['f_occupation'];
                $f_income_level = $context['parameters']['f_income_level'];
                $f_health_risk_factors = $context['parameters']['f_health_risk_factors'];
                $f_claim_type = $context['parameters']['f_claim_type'];
                // ทำสิ่งที่ต้องการกับข้อมูล
                // เช่น บันทึกลงฐานข้อมูล ประมวลผล หรือส่งค่ากลับไปยัง Dialogflow เป็นต้น
                // ตัวอย่าง:
                // $response = [
                //     'fulfillmentText' => "Response From Webhook ->"."Age : " . $f_age . ", Gender: " . $f_gender . ", Occupation: " .$f_occupation. ", Income Level: " .$f_income_level. ", Health Risk Factors: " .$f_health_risk_factors. ", Claim Type: " .$f_claim_type
                // ];

                // The API endpoint
                $url = "http://3d4f3cd7-4589-4a4d-8542-6052e1d3627d.southeastasia.azurecontainer.io/score";

                // The data to be sent in the POST request
                $data = [
                    "Inputs" => [
                        "input1" => [
                            [
                                "Age" => $f_age,
                                "Gender" => $f_gender,
                                "Occupation" => $f_occupation,
                                "Income Level" => $f_income_level,
                                "Health Risk Factors" => $f_health_risk_factors,
                                "Claim Type" => $f_claim_type,
                                "Classes" => "[1, 1, 1]"
                            ]
                        ]
                    ],
                    "GlobalParameters" => new stdClass()
                ];

                // JSON encode the data
                $jsonData = json_encode($data);

                // Initialize a cURL session
                $curl = curl_init($url);

                // Set cURL options for POST request
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonData);
                curl_setopt($curl, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer 38N54519tOLubQKsfTy0rywfe0k1cDPi'
                ]);

                // Execute the cURL session
                $response = curl_exec($curl);

                // Check for errors
                if ($response === false) {
                    $info = curl_getinfo($curl);
                    curl_close($curl);
                    die('Error occurred during curl exec. Additional info: ' . var_export($info));
                }

                // Close the cURL session
                curl_close($curl);

                // ส่ง JSON กลับไปยัง Dialogflow
                header('Content-Type: application/json');
                // echo json_encode($response);

                // แปลง JSON เป็น associative array
                $data = json_decode($response, true);

                // เข้าถึงค่าใน Scored Labels
                $scoredLabels = json_decode($data["Results"]["WebServiceOutput0"][0]["Scored Labels"]);       
    
                // เชื่อมต่อค่าใน $scoredLabels ด้วยเครื่องหมาย ','
                $scoredLabelsString = implode(', ', $scoredLabels);
                
       
                $chatbot2 = new Chatbot2($api_key);
                $chatbot2->processRequest($scoredLabelsString);
                
                // // สร้างข้อมูลในรูปแบบของ $response
                // $response = [
                //     'fulfillmentText' => json_encode($scoredLabelsString)
                // ];

                // // แสดงผลลัพธ์
                // echo json_encode($response);  
                break; // หลังจากพบข้อมูลที่ต้องการ ออกจากการวนลูป
            }else {
                $chatbot = new Chatbot($api_key);
                $chatbot->processRequest();
            }
            //break;
        }
    } else {
       
        // กรณีไม่พบข้อมูล outputContexts
        $chatbot = new Chatbot($api_key);
        $chatbot->processRequest();
        // $response = [
        //     'fulfillmentText' => "ไม่พบข้อมูล outputContexts"
        // ];

        // // ส่ง JSON กลับไปยัง Dialogflow
        // header('Content-Type: application/json');
        // echo json_encode($response);
    }
    
 