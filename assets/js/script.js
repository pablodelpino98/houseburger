// Muestra u oculta el menú de navegación (modo responsive)
function toggleMenu() {
  document.querySelector('.main-nav').classList.toggle('active');
}

// Muestra u oculta el carrito y lo actualiza si se muestra
function toggleCart() {
  const cartModal = document.getElementById('cartModal');
  cartModal.classList.toggle('show');
  if (cartModal.classList.contains('show')) {
    renderCart();
  }
}

// Cierra el carrito si se hace clic fuera de él
window.onclick = function (event) {
  const cartModal = document.getElementById('cartModal');
  if (event.target === cartModal) {
    cartModal.classList.remove('show');
  }
};

document.addEventListener('DOMContentLoaded', () => {
  // Referencias a elementos del modal
  const modal = document.getElementById('modal');
  const modalMessage = document.getElementById('modal-message');
  const refrescoSelect = document.getElementById('refrescoSelect');
  const confirmComboBtn = document.getElementById('confirmCombo');
  const cancelComboBtn = document.getElementById('cancelCombo');
  const closeModalBtn = document.getElementById('closeModal');

  let currentProduct = null;

  // Textos del modal con traducción
  const comboQuestion = translations.combo_question || '¿Desea añadir Papas Fritas Clásicas y un Refresco por 2,99€?';
  const yesAddCombo = translations.yes_add_combo || 'Sí, añadir combo';
  const noOnlyBurger = translations.no_only_burger || 'No, solo la hamburguesa';
  const closeText = translations.close || 'Cerrar';
  const addedToCart = translations.added_to_cart || 'Añadido al carrito';

  // Muestra el modal de combo
  const showComboModal = () => {
    modal.classList.add('show');
    modal.style.display = 'flex';
    refrescoSelect.style.display = 'none';
    confirmComboBtn.style.display = 'inline-block';
    cancelComboBtn.style.display = 'inline-block';
    closeModalBtn.style.display = 'none';
    modalMessage.textContent = comboQuestion;
    confirmComboBtn.textContent = yesAddCombo;
    cancelComboBtn.textContent = noOnlyBurger;
  };

  // Muestra el modal de confirmación tras añadir al carrito
  const showAddedModal = (message) => {
    modal.classList.add('show');
    modal.style.display = 'flex';
    refrescoSelect.style.display = 'none';
    confirmComboBtn.style.display = 'none';
    cancelComboBtn.style.display = 'none';
    closeModalBtn.style.display = 'inline-block';
    modalMessage.textContent = message;
    closeModalBtn.textContent = closeText;
  };

  // Cierra el modal
  const closeModal = () => {
    modal.classList.remove('show');
    modal.style.display = 'none';
    currentProduct = null;
  };

  // Añade un producto al carrito (vía fetch)
  const addToCart = (product, showMessage = true) => {
    fetch('../cart/add_to_cart.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(product)
    })
      .then(res => res.json())
      .then(() => {
        renderCart();
        if (showMessage) showAddedModal(addedToCart);
      })
      .catch(err => console.error('Error al añadir al carrito:', err));
  };

  // Detecta clic en botones de "Añadir al carrito"
  document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', (e) => {
      const productCard = e.target.closest('.product-card');
      const product = {
        product_id: parseInt(productCard.getAttribute('data-id')),
        name: productCard.getAttribute('data-name'),
        price: parseFloat(productCard.getAttribute('data-price')),
        type: productCard.getAttribute('data-type')
      };

      currentProduct = product;

      // Si es hamburguesa, pregunta por el combo
      if (product.type === 'hamburguesa') {
        showComboModal();
      } else {
        addToCart(product);
      }
    });
  });

  // Añadir solo la hamburguesa (sin combo)
  cancelComboBtn.addEventListener('click', () => {
    addToCart(currentProduct, false);
    showAddedModal(addedToCart);
  });

  // Confirmar combo: pide refresco, y luego añade
  confirmComboBtn.addEventListener('click', () => {
    if (refrescoSelect.style.display === 'none') {
      refrescoSelect.style.display = 'inline-block';
      modalMessage.textContent = translations['select_soft_drink'] || 'Seleccione el refresco para tu combo y confirma:';
      confirmComboBtn.textContent = yesAddCombo;
      cancelComboBtn.style.display = 'none';
    } else {
      const refresco = refrescoSelect.value;
      const combo = {
        product_id: currentProduct.product_id,
        name: `${currentProduct.name} + Papas Fritas Clásicas + ${refresco}`,
        price: currentProduct.price + 2.99,
        type: 'combo'
      };
      addToCart(combo, false);
      showAddedModal(addedToCart);
    }
  });

  // Botones para cerrar el modal
  closeModalBtn.addEventListener('click', closeModal);
  modal.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });
});

// Muestra el contenido del carrito consultando el backend
function renderCart() {
  fetch('../cart/get_cart.php')
    .then(res => res.json())
    .then(cart => {
      const cartContent = document.getElementById('cart-content');
      const totalElement = document.getElementById('total');
      const cartDataInput = document.getElementById('cart_data_input');

      if (!cartContent || !totalElement) return;

      if (cart.length === 0) {
        cartContent.innerHTML = `<p>${translations['cart_empty'] || 'El carrito está vacío.'}</p>`;
        totalElement.textContent = `${translations['total'] || 'Total'}: 0.00 €`;
        if (cartDataInput) cartDataInput.value = '';
        return;
      }

      cartContent.innerHTML = '';
      let total = 0;

      cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        const div = document.createElement('div');
        div.classList.add('cart-item');
        div.innerHTML = `
          <p><strong>${item.name}</strong> - ${item.quantity} x ${item.price.toFixed(2)} € = ${itemTotal.toFixed(2)} €</p>
          <button onclick="removeFromCart(${index})">${translations['remove'] || 'Eliminar'}</button>
        `;
        cartContent.appendChild(div);
        total += itemTotal;
      });

      totalElement.textContent = `${translations['total'] || 'Total'}: ${total.toFixed(2)} €`;
      if (cartDataInput) cartDataInput.value = JSON.stringify(cart);
    })
    .catch(error => {
      console.error('Error al renderizar carrito:', error);
    });
}

// Elimina un producto del carrito por su índice
function removeFromCart(index) {
  fetch('../cart/remove_from_cart.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ index })
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'ok') renderCart();
      else console.error('Error al eliminar:', data.message);
    })
    .catch(err => console.error('Error al eliminar producto:', err));
}

// Vacía el carrito completamente
function clearCart() {
  fetch('../cart/clear_cart.php')
    .then(res => res.json())
    .then(() => renderCart())
    .catch(err => console.error('Error al vaciar carrito:', err));
}
