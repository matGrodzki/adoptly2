<!DOCtype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f4f8;
            color: #333;
        }

        h1 {
            text-align: left;
            padding: 20px;
            background-color: #4CAF50;
            color: white;
            margin-bottom: 20px;
        }

        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
            border-radius: 8px;
        }

        th, td {
            padding: 12px 20px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
            font-weight: 500;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        button {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        button.delete {
            padding: 10px 15px;
            background-color: #B05C4C;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
        }

        button.addNew {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-size: 16px;
            width: 100px;
        }

        button:hover {
            background-color: #45a049;
        }

        button.delete:hover {
            background-color: #9E4447;
        }

        .button-container {
            display: flex;
            justify-content: flex-end;
            margin: 20px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .close:hover,
        .close:focus {
            color: #333;
            text-decoration: none;
            cursor: pointer;
        }

        select, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input {
            width: 96.5%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            width: 96.5%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        textarea {
            height: 100px;
            resize: vertical;
        }

        /* Form Section */
        form {
            display: flex;
            flex-direction: column;
        }
    </style>
</head>
<body>

    <?php
    // Fetch data from Xano API
    $apiUrl = "https://x8ki-letl-twmt.n7.xano.io/api:F9CheJiy/pets"; // Replace with your Xano API URL
    $response = file_get_contents($apiUrl);
    $data = json_decode($response, true);
    // Sort the data array alphabetically by name
      usort($data, function ($a, $b) {
          return strcmp($a['name'], $b['name']);
      });
    ?>
      <h1 style="text-align: left;">ADOPTLY - nasi podopieczni</h1>

    <div class="button-container">
        <button class="addNew" id="openModal">Dodaj</button>
    </div>

      <table>
          <tr>
              <th>ID</th>
              <th>Imię</th>
              <th>Gatunek</th>
              <th>Rasa</th>
              <th>Płeć</th>
              <th>Status</th>
              <th>Opis</th>
              <th>Akcje</th>
          </tr>
          <?php foreach ($data as $item): ?>
          <tr>
              <td><?= htmlspecialchars($item['id']) ?></td>
              <td><?= htmlspecialchars($item['name']) ?></td>
              <td><?= htmlspecialchars($item['species'] == 'dog' ? 'pies' : ($item['species'] == 'cat' ? 'kot' : $item['species'])) ?></td>
              <td><?= htmlspecialchars($item['breed']) ?></td>
              <td><?= htmlspecialchars($item['gender'] == 'male' ? 'samiec' : 'samiczka') ?></td>
              <td><?= htmlspecialchars($item['status'] == 'available' ? 'dostępny/a' : ($item['status'] == 'adoption in progress' ? 'w trakcie adopcji' : 'adoptowany/a')) ?></td>
              <td><?= htmlspecialchars($item['description']) ?></td>
              <td><button onclick="openEditModal(<?= htmlspecialchars($item['id']) ?>, '<?= htmlspecialchars($item['name']) ?>', '<?= htmlspecialchars($item['species']) ?>', '<?= htmlspecialchars($item['breed']) ?>', '<?= htmlspecialchars($item['gender']) ?>', '<?= htmlspecialchars($item['status']) ?>', '<?= htmlspecialchars($item['description']) ?>')">Edytuj</button><button class="delete" onclick="deletePet(<?= htmlspecialchars($item['id']) ?>)">Usuń</button></td>
          </tr>
          <?php endforeach; ?>
      </table>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <!-- DODAJ Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="newPetForm" method="POST" action="">
                <label for="name">Imię:</label>
                <input type="text" id="name" name="name" required>
                <label for="species">Gatunek:</label>
                <select id="species" name="species" required>
                    <option value="cat">Kot</option>
                    <option value="dog">Pies</option>
                </select>
                <label for="breed">Rasa:</label>
                <input type="text" id="breed" name="breed">
                <label for="gender">Płeć:</label>
                <select id="gender" name="gender" required>
                    <option value="male">samiec</option>
                    <option value="female">samiczka</option>
                </select>
                <label for="status">Status:</label>
                <select id="status" name="status" required>
                    <option value="available">dostępny/a</option>
                    <option value="adoption in progress">w trakcie adopcji</option>
                    <option value="adopted">adoptowany/a</option>
                </select>
                <label for="description">Opis:</label>
                <textarea id="description" name="description"></textarea>
                <button type="submit">Dodaj</button>
            </form>
        </div>
    </div>
    <div id="editModal" class="modal">
        <!-- EDYTUJ Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="editPetForm" method="POST" action="">
                <input type="hidden" id="editPetId" name="petId">
                <label for="editName">Imię:</label>
                <input type="text" id="editName" name="name" required>
                <label for="editSpecies">Gatunek:</label>
                <select id="editSpecies" name="species" required>
                    <option value="cat">Kot</option>
                    <option value="dog">Pies</option>
                </select>
                <label for="editBreed">Rasa:</label>
                <input type="text" id="editBreed" name="breed">
                <label for="editGender">Płeć:</label>
                <select id="editGender" name="gender" required>
                    <option value="male">samiec</option>
                    <option value="female">samiczka</option>
                </select>
                <label for="editStatus">Status:</label>
                <select id="editStatus" name="status" required>
                    <option value="available">dostępny/a</option>
                    <option value="adoption in progress">w trakcie adopcji</option>
                    <option value="adopted">adoptowany/a</option>
                </select>
                <label for="editDescription">Opis:</label>
                <textarea id="editDescription" name="description"></textarea>
                <button type="submit">Zapisz</button>
            </form>
        </div>
    </div>
    <script>
        function deletePet(petId) {
            if (confirm("Czy na pewno chcesz usunąć tego podopiecznego?")) {
                fetch(`https://x8ki-letl-twmt.n7.xano.io/api:F9CheJiy/pets/${petId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => {
                    if (response.ok) {
                        alert("Podopieczny został usunięty.");
                        location.reload(); // Reload the page to reflect the change
                    } else {
                        alert("Wystąpił błąd podczas usuwania podopiecznego.");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert("Wystąpił błąd podczas usuwania podopiecznego.");
                });
            }
        }
        // Get the modal
        var modal = document.getElementById("myModal");
        var editModal = document.getElementById("editModal");
        // Get the button that opens the modal
        var btn = document.getElementById("openModal");
        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];
        var editSpan = document.getElementsByClassName("close")[1];
        // When the user clicks the button, open the modal
        btn.onclick = function() {
            modal.style.display = "block";
        }
        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }
        editSpan.onclick = function() {
            editModal.style.display = "none";
        }
        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
            if (event.target == editModal) {
                editModal.style.display = "none";
            }
        }
        // Handle form submission
        const newPetForm = document.getElementById("newPetForm");
        newPetForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent default form submission
            const formData = new FormData(newPetForm);
            const name = formData.get('name');
            const species = formData.get('species');
            const breed = formData.get('breed');
            const gender = formData.get('gender');
            const status = formData.get('status');
            const description = formData.get('description');
            const apiUrl = 'https://x8ki-letl-twmt.n7.xano.io/api:F9CheJiy/pets'; // Replace with your Xano API URL
            fetch(apiUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    species: species,
                    breed: breed,
                    gender: gender,
                    status: status,
                    description: description
                })
            })
            .then(response => {
                if (response.ok) {
                    // Handle success (e.g., display a message, reload the page)
                    alert("Podopieczny dodany pomyślnie!");
                    modal.style.display = "none";
                    location.reload();
                } else {
                    // Handle error (e.g., display an error message)
                    console.error('Error:', error);
                    alert("Wystąpił błąd podczas dodawania podopiecznego.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Wystąpił błąd podczas dodawania podopiecznego.");
            });
        });
        // Edit Modal Functions
        function openEditModal(petId, name, species, breed, gender, status, description) {
            editModal.style.display = "block";
            document.getElementById("editPetId").value = petId;
            document.getElementById("editName").value = name;
            document.getElementById("editSpecies").value = species;
            document.getElementById("editBreed").value = breed;
            document.getElementById("editGender").value = gender;
            document.getElementById("editStatus").value = status;
            document.getElementById("editDescription").value = description;
        }
        const editPetForm = document.getElementById("editPetForm");
        editPetForm.addEventListener('submit', (event) => {
            event.preventDefault(); // Prevent default form submission
            const formData = new FormData(editPetForm);
            const petId = formData.get('petId');
            const name = formData.get('name');
            const species = formData.get('species');
            const breed = formData.get('breed');
            const gender = formData.get('gender');
            const status = formData.get('status');
            const description = formData.get('description');
            const apiUrl = `https://x8ki-letl-twmt.n7.xano.io/api:F9CheJiy/pets/${petId}`; // Replace with your Xano API URL
            fetch(apiUrl, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    name: name,
                    species: species,
                    breed: breed,
                    gender: gender,
                    status: status,
                    description: description
                })
            })
            .then(response => {
                if (response.ok) {
                    // Handle success (e.g., display a message, reload the page)
                    alert("Podopieczny zaktualizowany pomyślnie!");
                    editModal.style.display = "none";
                    location.reload();
                } else {
                    // Handle error (e.g., display an error message)
                    console.error('Error:', error);
                    alert("Wystąpił błąd podczas aktualizacji podopiecznego.");
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert("Wystąpił błąd podczas aktualizacji podopiecznego.");
            });
        });
    </script>
</body>
</html>