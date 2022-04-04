<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test</title>

    <style>
        #log {
            min-height: 300px;
            border: 2px solid black;
        }
        #input {
            width: 100%;
            margin-top: 10px;
            border: 2px solid black;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>

    <script>
        let request = {
            "meta": {
                "locale": "ru-RU",
                "timezone": "Asia/Almaty",
                "client_id": "ru.yandex.searchplugin/22.32 (OPPO CPH1893; android 9)",
                "interfaces": {
                    "screen": {},
                    "payments": {},
                    "account_linking": {}
                }
            },
            "session": {
                "message_id": 0,
                "session_id": "f5357ad2-c881-40e2-bd52-f04838d9eef5",
                "skill_id": "2333aacc-2c69-4472-8540-6cfca23ef630",
                "user": {
                    "user_id": "2A39887F6BE152F6D5A7E5C158ED9C431630C113F9EF120140E73D30BA4C6941"
                },
                "application": {
                    "application_id": "1F7355D902E191B6B004EDC66E57A16428B4166F0F19D0DCB0C7CF13E069CFA5"
                },
                "user_id": "1F7355D902E191B6B004EDC66E57A16428B4166F0F19D0DCB0C7CF13E069CFA5",
                "new": true
            },
            "request": {
                "command": "",
                "original_utterance": "",
                "nlu": {
                    "tokens": [],
                    "entities": [],
                    "intents": {}
                },
                "markup": {
                    "dangerous_context": false
                },
                "type": "SimpleUtterance"
            },
            "version": "1.0"
        }

        $(() => {
            $('#input').on('keypress', () => {
                if (event.keyCode === 13) {
                    let text = $('#input').val();
                    $('#log').append(`<div><b>Я</b>: ${text}</div>`);
                    request.request.command = text;

                    fetch('/', {
                        method: "POST",
                        body: JSON.stringify(request),
                    }).then(response => response.json()).then(data => {
                        $('#log').append(`<div><b>Алиса</b>: ${data.response.text}</div>`);
                        $('#input').val('');
                    });
                }
            });
        });
    </script>
</head>
<body>
    <div>
        <h1>Test</h1>
        <div id="log">
        </div>
        <input type="text" id="input" list="list" />
    </div>

    <datalist id="list">
        <?php foreach (SampleAlisa::instance()->meta() as $row) { ?>
            <option value="12313" />
        <?php } ?>
    </datalist>
</body>
</html>
