<?php
// Vérifie que le paramètre 'nom' est présent
if (!isset($_GET['nom']) || empty(trim($_GET['nom']))) {
    http_response_code(400);
    echo "<!DOCTYPE html><html lang='fr'><head><meta charset='UTF-8'><title>Erreur</title>
          <style>body{font-family:sans-serif;background:#fef2f2;color:#b91c1c;text-align:center;padding:50px;}
          h1{font-size:2rem;}p{margin-top:1rem;}</style></head><body>
          <h1>❌ Erreur : nom manquant</h1><p>Veuillez accéder à cette page via un lien personnalisé avec ?nom=VotreNom</p></body></html>";
    exit;
}

// Construit l’URL du livret d’accueil avec les paramètres GET
$livretUrl = "livret-accueil.php?" . http_build_query($_GET);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Séminaire 2025 – Programme</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-gray-50 text-gray-800">
  <header class="bg-white shadow p-4 flex justify-center">
    <img src="https://cdn.primagaz.fr/-/media/sites/france/settings/primagaz.svg" alt="Logo Séminaire" class="w-24 h-auto" />
  </header>

  <main class="max-w-4xl mx-auto px-6 py-10">
    <div class="text-center mb-10">
      <h1 class="text-4xl font-bold text-gray-900">Bienvenue <span class="text-blue-600"><?= htmlspecialchars($_GET['nom']) ?></span></h1>
      <p class="text-gray-600 mt-2">Voici votre programme personnalisé pour le séminaire.</p>

      <a href="<?= htmlspecialchars($livretUrl) ?>" class="inline-block mt-6">
        <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-full px-6 py-2 transition">
          📘 Livret d'accueil
        </button>
      </a>
    </div>

    <section class="bg-white shadow-md rounded-xl p-6 mb-10">
      <h2 class="text-2xl font-semibold text-blue-700 mb-4">📅 Mon agenda</h2>
      <ol class="space-y-6" id="agenda">
        <!-- Contenu JS -->
      </ol>
    </section>
  </main>

  <script>
    const p = new URLSearchParams(window.location.search);
    const get = (k) => p.get(k);
    const agenda = document.getElementById("agenda");

    const block = (classes, title, content = "", extra = "") => `
      <li>
        ${extra ? `<time class="block text-sm text-gray-500 font-medium mt-4 mb-1">${extra}</time>` : ""}
        <div class="${classes} p-4 rounded-lg shadow-sm border-l-4 border-blue-500">
          <p class="font-semibold text-gray-800">${title}</p>
          ${content ? `<p class="text-sm text-gray-600 mt-1">${content}</p>` : ""}
        </div>
      </li>
    `;

    const addBlock = (html) => {
      agenda.insertAdjacentHTML('beforeend', html);
    };

    // Jour 1
    addBlock(block("bg-blue-50", "14:00 – Safety induction", "Introduction welcome, déroulé, safety moment", "Lundi 8/09"));
    addBlock(block("bg-blue-50", "14:30 – Contexte Primagaz", "Ecosystem de Primagaz aujourd'hui"));
    addBlock(block("bg-blue-50", "15:10 – Look Back", "Mise à l’honneur collaborateurs / équipes"));
    addBlock(block("bg-blue-50", "16:00 – Primagaz d'aujourd'hui à demain"));
    addBlock(block("bg-green-50", "18:55 – Soirée", "1er retour : 23h25<br>2nd retour : 23h55"));

    // Jour 2
    addBlock(block("bg-blue-50", "9:00 – Safety", "", "Mardi 9/09"));
    addBlock(block("bg-white", "09:15 – Culture Managériale", "Notre Levier de Réussite (Postures, Pratiques, Performance)"));

    const ateliers = ["At1", "At2", "At3"];
    ateliers.forEach((at, i) => {
      const salle = get(`J2_${at}_Salle`);
      const table = get(`J2_${at}_Table`);
      if (salle && table) {
        addBlock(block("bg-white", `Atelier ${i+1}`, `Salle : ${salle}, Table : ${table}`));
      }
    });

    const team = get("J2_Team_Groupe");
    if (team) {
      addBlock(block("bg-white", "14:15 – Team Building", `Groupe : ${team}`));
    }

    addBlock(block("bg-green-50", "18:15 – Inclusivité chez PZ"));
    addBlock(block("bg-green-50", "20:45 – Soirée"));

    // Jour 3
    const j3 = ateliers.map((at, i) => {
      const salle = get(`J3_${at}_Salle`);
      return salle ? `Atelier ${i+1} : <span class='font-medium text-blue-700'>${salle}</span>` : null;
    }).filter(Boolean).join("<br>");

    if (j3) {
      addBlock(block("bg-white", "09:30 – Ateliers Pratico-Pratiques", j3, "Mercredi 10/09"));
    }

    addBlock(block("bg-white", "12:30 – Conclusion du séminaire"));
  </script>
</body>
</html>
