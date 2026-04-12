document.addEventListener("DOMContentLoaded", () => {
  const serviceSelector = document.getElementById("service-selector");
  const form = document.getElementById("form-service-admin");
  const btnNuevo = document.getElementById("btn-nuevo");

  async function loadServices() {
    try {
      const response = await fetch(
        "../controllers/ServiceController.php?action=getServices",
      );
      const result = await response.json();
      serviceSelector.innerHTML = "";
      if (result.status === "success") {
        result.services.forEach((s) => {
          const option = document.createElement("option");
          option.value = s.id;
          option.textContent = s.name;
          option.dataset.full = JSON.stringify(s);
          serviceSelector.appendChild(option);
        });
      }
    } catch (error) {
      console.error("Error:", error);
    }
  }

  async function loadQuotes() {
    const quotesList = document.getElementById("quotes-list");

    try {
      const response = await fetch(
        "../controllers/QuoteController.php?action=getQuotes",
      );
      const result = await response.json();

      if (result.status === "success" && result.quotes.length > 0) {
        quotesList.innerHTML = result.quotes
          .map(
            (q) => `
          <tr>
            <td style="font-family: 'JetBrains Mono', monospace; font-weight: bold;">
                <a href="quote.php?codigo=${q.codigo}" style="color: var(--dark-blue); text-decoration: underline;">
                    ${q.codigo}
                </a>
            </td>
            <td>${q.empresa}</td>
            <td style="font-family: 'JetBrains Mono', monospace; color: #38a169; font-weight: bold;">
                $${parseFloat(q.total).toFixed(2)}
            </td>
            <td>${q.fecha}</td>
          </tr>
        `,
          )
          .join("");
      } else {
        quotesList.innerHTML = `<tr><td colspan="4" style="text-align:center; padding: 20px;">No hay cotizaciones registradas en el sistema.</td></tr>`;
      }
    } catch (error) {
      console.error("Error al cargar cotizaciones:", error);
      quotesList.innerHTML = `<tr><td colspan="4" style="text-align:center; padding: 20px; color: red;">Error de conexión al cargar el historial.</td></tr>`;
    }
  }

  serviceSelector.addEventListener("change", () => {
    const selectedOption =
      serviceSelector.options[serviceSelector.selectedIndex];
    const service = JSON.parse(selectedOption.dataset.full);
    document.getElementById("service-id").value = service.id;
    document.getElementById("service-name").value = service.name;
    document.getElementById("service-category").value = service.category_id;
    document.getElementById("service-price").value = service.price;
    document.getElementById("service-description").value = service.description;
    document.getElementById("panel-title").textContent = "Modificar Servicio";
  });

  form.addEventListener("submit", async (e) => {
    e.preventDefault();
    const formData = {
      id: document.getElementById("service-id").value,
      name: document.getElementById("service-name").value,
      category_id: document.getElementById("service-category").value,
      price: document.getElementById("service-price").value,
      description: document.getElementById("service-description").value,
    };

    try {
      const response = await fetch(
        "../controllers/ServiceController.php?action=saveService",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(formData),
        },
      );
      const result = await response.json();
      if (result.status === "success") {
        Swal.fire("¡Éxito!", result.message, "success");
        resetForm();
        loadServices();
      }
    } catch (error) {
      Swal.fire("Error", "Error de conexión", "error");
    }
  });

  btnNuevo.addEventListener("click", () => resetForm());

  function resetForm() {
    form.reset();
    document.getElementById("service-id").value = "";
    document.getElementById("service-category").value = "1"; // Default a Web
    document.getElementById("panel-title").textContent = "Nuevo Servicio";
    serviceSelector.value = "";
  }

  loadServices();
  loadQuotes();
});
