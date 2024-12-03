const output = document.getElementById("output");

// Base URLs para las APIs disponibles
const baseURLs = {
    hello: "http://localhost:8000/api/hello",
    json: "http://localhost:8000/api/json",
    csv: "http://localhost:8000/api/csv",
};

let selectedEndpoint = "hello"; // Endpoint seleccionado por defecto

// Cambiar entre Hello, JSON y CSV
document.getElementById("hello-btn").addEventListener("click", () => {
    selectedEndpoint = "hello";
    updateActiveButton("hello-btn");
    output.value = "Selected: Class Hello";
});

document.getElementById("json-btn").addEventListener("click", () => {
    selectedEndpoint = "json";
    updateActiveButton("json-btn");
    output.value = "Selected: JSON";
});

document.getElementById("csv-btn").addEventListener("click", () => {
    selectedEndpoint = "csv";
    updateActiveButton("csv-btn");
    output.value = "Selected: CSV";
});

// Actualiza el botón activo
function updateActiveButton(activeButtonId) {
    document.getElementById("hello-btn").classList.remove("active");
    document.getElementById("json-btn").classList.remove("active");
    document.getElementById("csv-btn").classList.remove("active");
    document.getElementById(activeButtonId).classList.add("active");
}

// Función para realizar solicitudes HTTP
const fetchData = async (method, endpoint = "", body = null) => {
    try {
        const url = `${baseURLs[selectedEndpoint]}${endpoint}`;
        const options = {
            method,
            headers: { "Content-Type": "application/json" },
        };
        if (body) options.body = JSON.stringify(body);

        const response = await fetch(url, options);

        if (!response.ok) {
            // Mostrar error específico
            throw new Error(
                `Error ${response.status}: ${response.statusText || "Unknown Error"}`
            );
        }

        const data = await response.json();
        output.value = JSON.stringify(data, null, 2);
    } catch (error) {
        output.value = `Error: ${error.message}`;
    }
};

// Operaciones CRUD
document.getElementById("get-files").addEventListener("click", () => {
    fetchData("GET");
});

document.getElementById("store").addEventListener("click", () => {
    const filename = prompt(
        `Enter filename (e.g., file.${selectedEndpoint === "csv" ? "csv" : "txt"}):`
    );
    const content =
        selectedEndpoint === "csv"
            ? prompt(
                  "Enter file content in CSV format (e.g., header1,header2\\nvalue1,value2):"
              )
            : prompt("Enter file content:");
    if (filename && content) {
        // Si es CSV, validar que el contenido tenga al menos una línea con datos
        if (selectedEndpoint === "csv" && !content.includes(",")) {
            output.value = "Invalid CSV format! Ensure it includes commas.";
            return;
        }
        const requestBody = { filename, content };
        fetchData("POST", "", requestBody);
    } else {
        output.value = "Filename or content cannot be empty!";
    }
});

document.getElementById("show").addEventListener("click", () => {
    const filename = prompt("Enter filename to show:");
    if (filename) {
        fetchData("GET", `/${filename}`);
    } else {
        output.value = "Filename cannot be empty!";
    }
});

document.getElementById("update").addEventListener("click", () => {
    const filename = prompt("Enter filename to update:");
    const content =
        selectedEndpoint === "csv"
            ? prompt(
                  "Enter new content in CSV format (e.g., header1,header2\\nvalue1,value2):"
              )
            : prompt("Enter new content:");
    if (filename && content) {
        // Validación específica para CSV
        if (selectedEndpoint === "csv" && !content.includes(",")) {
            output.value = "Invalid CSV format! Ensure it includes commas.";
            return;
        }
        const requestBody = { content };
        fetchData("PUT", `/${filename}`, requestBody);
    } else {
        output.value = "Filename or content cannot be empty!";
    }
});

document.getElementById("delete").addEventListener("click", () => {
    const filename = prompt("Enter filename to delete:");
    if (filename) {
        fetchData("DELETE", `/${filename}`);
    } else {
        output.value = "Filename cannot be empty!";
    }
});

// Botón enviar (opcional para futuras funcionalidades)
document.getElementById("send").addEventListener("click", () => {
    alert("Send action triggered!");
});
