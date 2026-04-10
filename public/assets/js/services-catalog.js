console.log("Working!");

// ALERTAS
const cartAlert = () => {
  Swal.fire({
    title: "¡Enhorabuena!",
    text: "El item ha sido añadido al carrito",
    icon: "success",
    timer: 2000,
    timerProgressBar: true,
  });
};

const buyMinAlert = () => {
  Swal.fire({
    title: "¡Carro vacío!",
    text: "No puedes generar una cotización con el carro vacío",
    icon: "error",
    timer: 2000,
    timerProgressBar: true,
  });
};

const genericErrorAlert = (mensaje) => {
  Swal.fire({
    title: "¡Error!",
    text: `${mensaje}`,
    icon: "error",
    timer: 3000,
    timerProgressBar: true,
  });
};

// OBTENER LA DATA DE LA API

// SERVICIOS

const getServicesData = async () => {
  try {
    let url = "../controllers/ServiceController.php?action=getServices";
    const response = await fetch(url);
    const data = await response.json();

    console.log(data);
    sessionStorage.setItem("services", JSON.stringify(data.services));
  } catch (error) {
    console.error("Error al contactar a la api", error);
  }
};

// CARRITO EN SESSION
const getCartData = async () => {
  try {
    let url = "../controllers/CartController.php?action=getCartData";
    const response = await fetch(url);
    const data = await response.json();

    return data;
  } catch (error) {
    console.error("Error al contactar a la api", error);
  }
};

// OBTENER Y DIBUJAR CATEGORÍAS
const loadCategories = async () => {
  try {
    const response = await fetch(
      "../controllers/CategoryController.php?action=getCategories",
    );
    const data = await response.json();

    if (data.status === "success") {
      const navList = document.getElementById("category-filters");

      // 1. Mapeamos las categorías que vienen de la base de datos
      const categoryButtons = data.categories
        .map((cat) => {
          return `<li><button class="button-filter" onclick="fillServices('${cat.name}')">${cat.name}</button></li>`;
        })
        .join("");

      // 2. Inyectamos los botones y agregamos el botón estático de "Todos" al final
      navList.innerHTML =
        categoryButtons +
        `<li><button class="button-filter" onclick="fillServices()">Todos</button></li>`;
    }
  } catch (error) {
    console.error("Error al cargar las categorías:", error);
    document.getElementById("category-filters").innerHTML =
      `<li><button class="button-filter">Error al cargar</button></li>`;
  }
};

// AGREGAR AL CARRITO
const addToCart = async (serviceId, isInCart = false) => {
  let url = "../controllers/CartController.php?action=addToCart";

  try {
    const response = await fetch(url, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },

      body: JSON.stringify({ id: serviceId }),
    });

    if (!response.ok) {
      const errorData = await response.json();
      throw new Error(errorData.message || "Error al agregar al carrito");
    }

    const data = await response.json();

    if (data.status === "success") {
      await fillCart();
      if (!isInCart) {
        cartAlert();
      }
    }
  } catch (error) {
    console.error("Error en la comunicación con la API:", error.message);
    genericErrorAlert(error.message);
  }
};

// DECREMENTAR EN EL CARRITO
const decreaseQuantity = async (serviceId) => {
  try {
    const response = await fetch(
      "../controllers/CartController.php?action=decreaseQuantity",
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: serviceId }),
      },
    );

    const data = await response.json();

    if (data.status === "success") {
      await fillCart();
    }
  } catch (error) {
    console.error("Error al actualizar cantidad:", error);
  }
};

// REMOVER ITEM DEL CARRITO
const removeFromCart = async (serviceId) => {
  try {
    const response = await fetch(
      "../controllers/CartController.php?action=removeFromCart",
      {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: serviceId }),
      },
    );

    const data = await response.json();

    if (data.status === "success") {
      await fillCart();
    }
  } catch (error) {
    console.error("Error al actualizar cantidad:", error);
  }
};

// LLENAR LOS SERVICIOS EN PANTALLA
const fillServices = async (category = null) => {
  await getServicesData();

  const cardsContainer = document.getElementById("cards");
  let servicesData = JSON.parse(sessionStorage.getItem("services"));

  if (!servicesData) return;

  if (category !== null) {
    servicesData = servicesData.filter(
      (service) => service.category_name === category,
    );
  }

  const servicesCards = servicesData
    .map((service) => {
      return `
      <article class="card">
        <button class="card-header ${service.category_name.toLowerCase()}" onclick="addToCart(${service.id})">
          <h3>${service.name}</h3>
        </button>
        <div class="card-body">
          <p>${service.description}</p>
          <div class="card-footer">
            <span class="price">$${service.price}</span>
            <span class="tag ${service.category_name.toLowerCase()}">${service.category_name}</span>
          </div>
        </div>
      </article>
    `;
    })
    .join("");

  cardsContainer.innerHTML = servicesCards;
};

// ACTUALIZAR CARRITO
const fillCart = async () => {
  try {
    const data = await getCartData();

    const cartContainer = document.querySelector(".cart-items");
    const totalPriceElement = document.querySelector(".total-price");

    if (data.status === "success") {
      cartContainer.innerHTML = data.cart
        .map(
          (item) => `
        <div class="cart-item">
          <div class="item-info">
            <h4>${item.name}</h4>
            <p>$${parseFloat(item.price).toFixed(2)} x ${item.quantity}</p>
          </div>
          <div class="item-actions">
            <button class="action add-item" onclick="decreaseQuantity(${item.id})">
              <i class="fa-solid fa-caret-left"></i>
            </button>
            <button class="action remove-item" onclick="removeFromCart(${item.id})">
              <i class="fa-solid fa-trash"></i>
            </button>
            <button class="action add-item" onclick="addToCart(${item.id},${true})">
              <i class="fa-solid fa-caret-right"></i>
            </button>
          </div>
        </div>
      `,
        )
        .join("");

      totalPriceElement.textContent = `$${parseFloat(data.total_general).toFixed(2)}`;
    }
  } catch (error) {
    console.error("Error al llenar el carrito:", error);
  }
};

// EJECUCIÓN INICIAL
loadCategories();
fillServices();
fillCart();

// MANEJO DEL CARRITO (SIDEBAR)
const toggleCart = () => {
  const cart = document.getElementById("shopping-cart");
  const overlay = document.getElementById("cart-overlay");

  cart.classList.toggle("active");
  overlay.classList.toggle("active");
};

// REDIRECCION AL HISTORIAL
const redirectList = () => {
  window.location.href = "quotes.php";
};

// Abrir el modal desde el botón del carrito
document.querySelector(".checkout-btn").addEventListener("click", () => {
  const cartItems = document.querySelector(".cart-items").children.length;
  if (cartItems === 0) {
    buyMinAlert();
    return;
  }
  document.getElementById("quote-modal").style.display = "flex";
});

// MANEJO REAL DEL FORMULARIO HACIA EL BACKEND
document
  .getElementById("quote-form")
  .addEventListener("submit", async function (e) {
    e.preventDefault();

    // Recolectar datos
    const datosFormulario = {
      nombre: this.querySelector('input[placeholder="Ej. Rodrigo Mejía"]')
        .value,
      empresa: this.querySelector('input[placeholder="Ej. Apple"]').value,
      correo: this.querySelector('input[type="email"]').value,
    };

    try {
      // Enviar datos al endpoint process-quote.php
      const response = await fetch(
        "../controllers/QuoteController.php?action=createQuote",
        {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(datosFormulario),
        },
      );

      const resultado = await response.json();

      if (resultado.status === "success") {
        // Si el backend responde éxito, redirigimos a la tabla con el código generado
        window.location.href = `quote.php?codigo=${resultado.codigo}`;
      } else {
        // Mostrar el error
        genericErrorAlert(resultado.mensaje);
      }
    } catch (error) {
      console.error("Error al procesar la cotización:", error);
      genericErrorAlert(
        "Hubo un fallo en el servidor al generar la cotización.",
      );
    }
  });

// Cerrar modal si se hace clic fuera del contenido
window.onclick = function (event) {
  let modal = document.getElementById("quote-modal");
  if (event.target == modal) {
    modal.style.display = "none";
  }
};
