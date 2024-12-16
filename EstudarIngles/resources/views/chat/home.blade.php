<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 d-flex">

                    <div class="align-self-center text-center items-center">

                        <div class="row">
                            <div class="col-6 d-none hidden">
                                <h1>Text To Speech</h1>
                                <button class="btn btn-secondary" onclick="speak()"><i class="fa fa-solid fa-volume-down"></i></button>
                            </div>
                        </div>
                        <br /><br /><br />

                        <div class="row">
                            <div class="col-sm-4 d-flex justify-content-center">
        {{--                        <button class="btn btn-secondary" id="startButton">Start Voice Input <i class="fa fa-solid fa-microphone"></i> </button>--}}
                                <button class="btn btn-secondary" id="startButton">
                                    <x-microphone></x-microphone>
                                </button>
                                <div id="loading-audio" class="hidden">
                                    <x-loading-audio></x-loading-audio>
                                </div>
                                <br /><br /><br />
                                <div id="output"></div>
                            </div>
                        </div>

                    </div>



                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function speak(textContent) {
        // Create a SpeechSynthesisUtterance
        if(!textContent) {
            textContent = "Welcome to this tutorial!";
        }
        const utterance = new SpeechSynthesisUtterance(textContent);
        utterance.lang = "en-US";
        // Select a voice
        const voices = speechSynthesis.getVoices();
        utterance.voice = voices[0]; // Choose a specific voice

        // Speak the text
        speechSynthesis.speak(utterance);
    }

    function getApiResult(text) {
        if(!text) {
            return;
        }
        const options = {
            method: 'GET',
            headers: {'Content-Type': 'application/json', 'User-Agent': 'insomnia/10.1.1'},
            // body: '{"contents":[{"parts":[{"text":"Estou aprendendo inglês e gostaria que você me corrija se eu estiver errado e responda como se fosse uma conversa entre dois conhecidos: \'Hello, how are you?\'"}]}]}'
        };

        const encoded = encodeURI(text);
        const url = '{{ route('interation.show', 1) }}?text=' + encoded;
        return fetch(url, options)
            .then(response => response.json())
            .then(response => {
                console.log(response)
                return response;
            })
            .catch(err => console.error(err));
    }


    // JavaScript code will go here
    const startButton = document.getElementById('startButton');
    const outputDiv = document.getElementById('output');

    const loadingAudio = document.getElementById('loading-audio');

    const recognition = new (window.SpeechRecognition || window.webkitSpeechRecognition || window.mozSpeechRecognition || window.msSpeechRecognition)();
    recognition.lang = 'en-US';

    recognition.onstart = () => {
        //startButton.textContent = 'Listening...';
        startButton.classList.add('hidden');
        loadingAudio.classList.remove('hidden');
    };

    recognition.onresult = (event) => {
        const transcript = event.results[0][0].transcript;
        let response = getApiResult(transcript);
        window.teste = response
        response.then((resp) => {
            //localStorage.setItem('iaresponse', JSON.stringify(resp))
            //console.log(r.candidates[0].content.parts[0].text)
            result = resp.candidates[0].content.parts[0].text;
            outputDiv.textContent = result;
            speak(outputDiv.textContent)

        })
    };

    recognition.onend = () => {
        //speak(outputDiv.textContent)
        setTimeout(() => {
            // startButton.innerHTML = 'Start Voice Input <i class=\"fa fa-solid fa-microphone\"></i>'
            startButton.classList.remove('hidden');
            loadingAudio.classList.add('hidden');
        }, 500)
    };

    startButton.addEventListener('click', () => {
        recognition.start();
    });
</script>
