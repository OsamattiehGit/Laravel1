document.querySelectorAll('.field-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const selectedField = btn.dataset.field;

        fetch('/suggest-course/result', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ field: selectedField })
        })
        .then(response => response.json())
        .then(data => {
            const resultBox = document.getElementById('course-result');
            resultBox.innerHTML = '';

            if (data.length === 0) {
                resultBox.innerHTML = "<p>No matching courses found.</p>";
            } else {
                data.forEach(course => {
                    const card = `<div class="course-card">
                        <h4>${course.name}</h4>
                        <p>${course.description}</p>
                        <small>Category: ${course.category}</small>
                    </div>`;
                    resultBox.innerHTML += card;
                });
            }
        });
    });
});
