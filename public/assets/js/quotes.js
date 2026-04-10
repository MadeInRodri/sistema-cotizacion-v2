console.log("Working!");

document.addEventListener("DOMContentLoaded", () => {
  const currentPath = window.location.pathname;

  // Enrutador del lado del cliente para saber qué función ejecutar
  if (currentPath.includes("quotes.php")) {
    loadQuotesHistory();
  } else if (currentPath.includes("quote.php")) {
    loadQuoteDetail();
  }
});

// Función para el Historial (view-quotes.php)
const loadQuotesHistory = async () => {
  const tbody = document.getElementById("quotes-table-body");

  try {
    const response = await fetch(
      "../controllers/QuoteController.php?action=getUserQuotes",
    );
    const data = await response.json();

    if (data.status === "success" && data.quotes.length > 0) {
      tbody.innerHTML = data.quotes
        .map(
          (q) => `
        <tr>
            <td class="font-mono">${q.codigo}</td>
            <td>${q.cliente}</td>
            <td>${q.empresa}</td>
            <td>${q.fecha}</td>
            <td class="text-right font-mono"><strong>$${parseFloat(q.total).toFixed(2)}</strong></td>
            <td>
                <a href="quote.php?codigo=${q.codigo}" class="badge" style="text-decoration:none">
                    <i class="fa-solid fa-eye"></i> Ver Detalle
                </a>
            </td>
        </tr>
      `,
        )
        .join("");
    } else {
      tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; padding: 2rem;">No hay cotizaciones registradas aún.</td></tr>`;
    }
  } catch (error) {
    console.error("Error cargando historial:", error);
    tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; padding: 2rem;">Error al cargar los datos.</td></tr>`;
  }
};

// Función para el Detalle (view-table-quote.php)
const loadQuoteDetail = async () => {
  // Extraer el código de la URL (?codigo=COT-...)
  const urlParams = new URLSearchParams(window.location.search);
  const codigo = urlParams.get("codigo");

  if (!codigo) {
    window.location.href = "cart.php";
    return;
  }

  try {
    const response = await fetch(
      `../controllers/QuoteController.php?action=getQuoteDetail&codigo=${codigo}`,
    );
    const data = await response.json();

    if (data.status === "success") {
      const q = data.quote;

      // Llenar Cabeceras
      document.getElementById("quote-client-name").textContent =
        `${q.cliente} - ${q.empresa}`;
      document.getElementById("quote-code").textContent = q.codigo;
      document.getElementById("quote-date").textContent = q.fecha_vencimiento;

      // Llenar Tabla de Items
      const tbody = document.getElementById("quote-items-body");
      tbody.innerHTML = q.items
        .map(
          (item) => `
        <tr>
            <td data-label="Servicio">
                <strong>${item.name}</strong><br>
                <small>${item.description}</small>
            </td>
            <td data-label="Categoría">
                <span class="badge ${item.category_name.toLowerCase()}">${item.category_name}</span>
            </td>
            <td data-label="Cantidad" class="text-right">${item.quantity}</td>
            <td data-label="Precio" class="text-right font-mono">$${parseFloat(item.price).toFixed(2)}</td>
            <td data-label="Subtotal" class="text-right font-mono">$${parseFloat(item.subtotal).toFixed(2)}</td>
        </tr>
      `,
        )
        .join("");

      // Llenar Totales
      document.getElementById("quote-subtotal").textContent =
        `$${parseFloat(q.subtotal).toFixed(2)}`;
      document.getElementById("quote-discount").textContent =
        `-$${parseFloat(q.descuento).toFixed(2)}`;
      document.getElementById("quote-tax").textContent =
        `$${parseFloat(q.impuesto).toFixed(2)}`;
      document.getElementById("quote-total").textContent =
        `$${parseFloat(q.total).toFixed(2)}`;

      // Actualizar el título de la pestaña
      document.title = `Detalle de Cotización - ${q.codigo}`;
    } else {
      alert("No se encontró la cotización");
      window.location.href = "view-quotes.php";
    }
  } catch (error) {
    console.error("Error cargando detalle:", error);
  }
};
