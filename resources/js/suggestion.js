const chatLog = document.getElementById('chat-log');
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

let step = 0;
let selectedPath = {
    intent: '',
    field: '',
    preference: ''
};

const steps = [
    {
        text: "Welcome, Let us know your current status?",
        options: [
            { label: "Looking For Job", icon: "job" },
            { label: "IT to Non-IT Job Shift", icon: "switch" },
            { label: "Upskill IT Job", icon: "upskill" }
        ]
    },
    {
        text: "Great! Let me help you",
        options: [
            { label: "Discover Course", icon: "discover" },
            { label: "Suggest Course", icon: "suggest" }
        ]
    },
    {
        text: "Select the field you're interested in",
        options: [
            { label: "IT Field" },
            { label: "Non IT Field" }
        ]
    },
    {
        text: "IT Field, then what do you prefer now?",
        options: [
            { label: "Coding", icon: "code" },
            { label: "No Coding", icon: "nocode" }
        ]
    },
    {
        text: "Wow, you chose coding. What's next?",
        options: [
            { label: "Front End", icon: "frontend" },
            { label: "Back End", icon: "backend" },
            { label: "Full Stack", icon: "fullstack" }
        ]
    },
    {
        text: "Excellent! Click next to get into",
        options: [{ label: "Next" }]
    }
];

function createBubble(content, sender = 'ezy', icon = null) {
    const wrapper = document.createElement('div');
    wrapper.className = `chat-bubble ${sender}`;

    if (sender === 'ezy') {
        wrapper.innerHTML = `<div class="avatar">EZY</div><div class="msg">${content}</div>`;
    } else {
        const img = icon ? `<img src='/images/icons/${icon}.svg' class='icon-img'/>` : '';
        wrapper.innerHTML = `<div class="msg">${img} ${content}</div>`;
    }
    chatLog.appendChild(wrapper);
    chatLog.scrollTop = chatLog.scrollHeight;
}

function renderOptions(options) {
    const row = document.createElement('div');
    row.className = 'option-row';
    row.dataset.step = step;

    options.forEach(opt => {
        const btn = document.createElement('button');
        btn.className = 'option-btn';
        btn.innerHTML = opt.icon ? `<img src='/images/icons/${opt.icon}.svg'/> ${opt.label}` : opt.label;
        btn.onclick = () => {
            // Disable all buttons in this step
            const allBtns = document.querySelectorAll(`.option-row[data-step="${step}"] .option-btn`);
            allBtns.forEach(b => b.disabled = true);

            handleChoice(opt);
        };
        row.appendChild(btn);
    });

    chatLog.appendChild(row);
    chatLog.scrollTop = chatLog.scrollHeight;
}

function handleChoice(opt) {
    createBubble(opt.label, 'user', opt.icon);

    if (step === 0) selectedPath.intent = opt.label;
    if (step === 2) selectedPath.field = opt.label;
    if (step === 3) selectedPath.preference = opt.label;

    step++;
    if (step < steps.length) {
        setTimeout(() => {
            createBubble(steps[step].text);
            renderOptions(steps[step].options);
        }, 300);
    } else {
        // Reached end, fetch results
        fetch('/suggest-course/result', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            body: JSON.stringify({
                field: selectedPath.field,
                intent: selectedPath.intent
            })
        })
        .then(res => res.json())
        .then(data => {
            createBubble("Here are some suggestions for you:");
            const ul = document.createElement('ul');
            ul.classList.add('course-list');
            data.courses.forEach(course => {
                const li = document.createElement('li');
                li.innerHTML = `<i class="fa fa-graduation-cap"></i> ${course}`;
                ul.appendChild(li);
            });
            chatLog.appendChild(ul);

            // Restart Button
            const restartBtn = document.createElement('button');
            restartBtn.className = 'option-btn';
            restartBtn.innerText = "Restart Chat";
            restartBtn.onclick = () => location.reload();
            const wrap = document.createElement('div');
            wrap.className = 'option-row';
            wrap.appendChild(restartBtn);
            chatLog.appendChild(wrap);
        });
    }
}

// Init
window.addEventListener('DOMContentLoaded', () => {
    createBubble(steps[0].text);
    renderOptions(steps[0].options);
});
