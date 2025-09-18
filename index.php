<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="origin-trial" content="Arba/apJET5TfJ9rvNDWjR3LaMj/VuHoRk4qVnVQPd3OnMqDgk64bSCB4i+PTP11DAVu+A/VENkl3JJ13/JxKw8AAAB0eyJvcmlnaW4iOiJodHRwczovL2ZsaXhiZXJyeS5jb206NDQzIiwiZmVhdHVyZSI6IkFJUHJvbXB0QVBJTXVsdGltb2RhbElucHV0IiwiZXhwaXJ5IjoxNzc0MzEwNDAwLCJpc1N1YmRvbWFpbiI6dHJ1ZX0=">
  <title>CharacterView AI | Flixberry</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <!-- Header with Logo -->
  <header class="app-header">
    <div class="logo-banner">
      <img src="flixberry characterview ai logo.png" alt="Flixberry CharacterView AI Logo">
    </div>
  </header>

  <!-- Upload Section -->
  <h1>Upload Your Character</h1>
  <input type="file" id="characterInput" accept="image/*">

  <!-- Preview Image -->
  <div class="preview-wrapper">
    <img id="preview" class="centered-preview" style="display:none;" alt="Character preview">
  </div>

  <!-- Fallback Banner -->
  <div class="fallback-banner">
    <strong>Preview Mode:</strong> These are mock views until Chrome AI is fully enabled.
  </div>

  <!-- Generated Views -->
  <div id="generated-views">
    <!-- AI-generated views will appear here -->
  </div>

  <!-- Script -->
  <script>
    document.getElementById('characterInput').addEventListener('change', function(e) {
      const file = e.target.files[0];
      const reader = new FileReader();

      reader.onload = async function(event) {
        const imageData = event.target.result;
        const preview = document.getElementById('preview');
        preview.src = imageData;
        preview.style.display = '';

        // Insert API status banner
        const apiStatus = document.createElement('div');
        apiStatus.textContent = window.promptApiGeminiNano ? "Chrome AI detected ✅" : "Chrome AI not available ❌";
        apiStatus.style.marginTop = "10px";
        apiStatus.style.fontWeight = "bold";
        apiStatus.style.color = "#333";
        document.body.insertBefore(apiStatus, document.getElementById('generated-views'));

        const prompt = `Generate front, back, left, and right views of this character in cartoon style.`;

        try {
          // Fallback if API is unavailable
          if (!window.promptApiGeminiNano || !window.promptApiGeminiNano.generate) {
            alert("Prompt API not available yet. Showing mock views for demo.");

            const response = {
              views: [
                { label: 'front', image: 'https://via.placeholder.com/200x200?text=Front+View' },
                { label: 'back', image: 'https://via.placeholder.com/200x200?text=Back+View' },
                { label: 'left', image: 'https://via.placeholder.com/200x200?text=Left+View' },
                { label: 'right', image: 'https://via.placeholder.com/200x200?text=Right+View' }
              ]
            };

            const viewsContainer = document.getElementById('generated-views');
            viewsContainer.innerHTML = '';

            response.views.forEach((view) => {
              const img = document.createElement('img');
              img.src = view.image;
              img.alt = view.label;
              viewsContainer.appendChild(img);

              const downloadBtn = document.createElement('a');
              downloadBtn.href = view.image;
              downloadBtn.download = `character-${view.label}.png`;
              downloadBtn.textContent = `Download ${view.label}`;
              downloadBtn.style.display = 'block';
              downloadBtn.style.marginBottom = '1rem';
              viewsContainer.appendChild(downloadBtn);
            });

            return;
          }

          // Real Prompt API call
          const response = await window.promptApiGeminiNano.generate({
            input: {
              image: imageData,
              text: prompt
            },
            output: "image"
          });

          const viewsContainer = document.getElementById('generated-views');
          viewsContainer.innerHTML = '';

          response.views.forEach((view) => {
            const img = document.createElement('img');
            img.src = view.image;
            img.alt = view.label;
            viewsContainer.appendChild(img);

            const downloadBtn = document.createElement('a');
            downloadBtn.href = view.image;
            downloadBtn.download = `character-${view.label}.png`;
            downloadBtn.textContent = `Download ${view.label}`;
            downloadBtn.style.display = 'block';
            downloadBtn.style.marginBottom = '1rem';
            viewsContainer.appendChild(downloadBtn);
          });

        } catch (error) {
          console.error('Prompt API error:', error);
        }
      };

      reader.readAsDataURL(file);
    });
  </script>
</body>
</html>
