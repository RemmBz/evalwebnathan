document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector("#contactForm");
  const confirmation = document.querySelector("#confirmation");

  form.addEventListener("submit", (e) => {
    e.preventDefault();

    const prenom = document.querySelector("#prenom").value.trim();
    const email = document.querySelector("#email").value.trim();

    if (prenom && email) {
      confirmation.textContent = "✅ Votre demande a bien été envoyée !";
      confirmation.style.color = "green";
      confirmation.style.display = "block";
      form.reset();

      const formData = new FormData();
      formData.append("prenom", prenom);
      formData.append("email", email);

      fetch("./traitement.php", {
        method: "POST",
        body: formData
      })
        .then((response) => response.json())
        .then((data) => {
          if (!data.success) {
            confirmation.textContent = "⚠️ Erreur : " + data.message;
            confirmation.style.color = "red";
            confirmation.style.display = "block";
          }
        })
        .catch((error) => {
          console.error("Erreur:", error);
          confirmation.textContent = "⚠️ Erreur de connexion au serveur";
          confirmation.style.color = "red";
          confirmation.style.display = "block";
        });
    } else {
      confirmation.textContent = "❌ Les champs ne sont pas corrects";
      confirmation.style.color = "red";
      confirmation.style.display = "block";
    }
  });
});
