document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".dotit-newsletter-form");

    if (!form) {
        console.error("Formularz newslettera nie został znaleziony.");
        return;
    }

    form.addEventListener("submit", async function (event) {
        event.preventDefault(); // Zapobiega domyślnej akcji wysłania formularza

        const emailInput = form.querySelector('input[name="email"]');
        const messageDiv = form.querySelector(".form-message");

        if (!emailInput) {
            console.error("Pole email nie zostało znalezione.");
            return;
        }

        const email = emailInput.value.trim();
        if (!email) {
            messageDiv.textContent = "Podaj poprawny adres e-mail.";
            messageDiv.classList.remove("d-none");
            messageDiv.classList.add("text-danger");
            return;
        }

        try {
            const response = await fetch(form.action, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({ email: email }),
            });

            const data = await response.json();

            if (response.ok) {
                messageDiv.textContent = "Dziękujemy za subskrypcję!";
                messageDiv.classList.remove("d-none", "text-danger");
                messageDiv.classList.add("text-success");
                form.reset();
            } else {
                messageDiv.textContent = data["hydra:description"] || "Wystąpił błąd.";
                messageDiv.classList.remove("d-none", "text-success");
                messageDiv.classList.add("text-danger");
            }
        } catch (error) {
            console.error("Błąd sieci:", error);
            messageDiv.textContent = "Wystąpił problem z połączeniem.";
            messageDiv.classList.remove("d-none", "text-success");
            messageDiv.classList.add("text-danger");
        }
    });
});
