<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>CharacterView AI | Flixberry</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Upload Your Character</h1>
    <input type="file" id="characterInput" accept="image/*">
    <br>
    <img id="preview" style="display:none;" alt="Character preview">


    <div style="background:#fff3cd; padding:10px; border-radius:8px; margin-top:20px; font-family:sans-serif;">
      <strong>Preview Mode:</strong> These are mock views until Chrome AI is fully enabled.
    </div>


    <div id="generated-views">
      <!-- AI-generated views will appear here -->
    </div>




    <script>
  document.getElementById('characterInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const reader = new FileReader();

    reader.onload = async function(event) {
      const imageData = event.target.result;
      const preview = document.getElementById('preview');
      preview.src = imageData;
      preview.style.display = 'block';

      const prompt = `Generate front, back, left, and right views of this character in cartoon style.`;

      try {
        // Fallback message if API isn't available
        if (!window.promptApiGeminiNano || !window.promptApiGeminiNano.generate) {
          alert("Prompt API not available yet. Showing mock views for demo.");

          // MOCK response for demo purposes
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

        // Real Prompt API call (when available)
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
