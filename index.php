<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Denisse's Dream Garden - Catálogo Botánico de El Salvador</title>
    <link rel="stylesheet" href="style.css?v=302.0">
    
    <!-- LIBRERÍAS DE CHART.JS, HTML2CANVAS Y JSPDF -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <style>
        .btn-remove-img {
            position: absolute !important;
            top: 8px !important;
            right: 8px !important;
            background: #f8a2c4 !important;
            color: #ffffff !important;
            border: none !important;
            border-radius: 50% !important;
            width: 28px !important;
            height: 28px !important;
            font-size: 0.9rem !important;
            font-weight: bold !important;
            cursor: pointer !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2) !important;
            transition: background 0.2s ease, transform 0.2s ease !important;
            z-index: 10 !important;
        }

        .btn-remove-img:hover {
            background: #e07a9e !important;
            transform: scale(1.1) !important;
        }

        .field-warn-msg {
            font-size: 0.75rem;
            color: #dc2626;
            margin-top: 4px;
            display: none;
            font-weight: 700;
        }
        .field-warn-msg.active {
            display: block;
        }

        .preview-container {
            position: relative;
            display: block;
            width: 100%;
            margin-top: 10px;
        }

        .preview-container img {
            width: 100% !important;
            max-width: 100% !important;
            height: 250px !important;
            object-fit: cover !important;
            border-radius: 12px !important;
            border: 2px dashed #f8a2c4 !important;
            background-color: #fff0f5 !important;
            display: block !important;
        }
    </style>
</head>
<body>

    <!-- DEFINICIÓN VECTORIAL SVG -->
    <svg class="sprite" style="display:none;">
        <symbol id="eye" viewBox="0 0 24 24">
            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <circle cx="12" cy="12" r="3" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </symbol>

        <symbol id="icon-flower" viewBox="0 0 100 100">
            <g fill="#f8a2c4" opacity="0.85">
                <circle cx="50" cy="30" r="18" />
                <circle cx="69" cy="44" r="18" />
                <circle cx="62" cy="67" r="18" />
                <circle cx="38" cy="67" r="18" />
                <circle cx="31" cy="44" r="18" />
                <circle cx="50" cy="50" r="14" />
            </g>
            <path d="M50 35 L54 45 L64 43 L56 50 L61 59 L50 53 L39 59 L44 50 L36 43 L46 45 Z" fill="#ffffff" opacity="0.95"/>
            <circle cx="50" cy="35" r="2.5" fill="#ffffff"/>
            <circle cx="64" cy="43" r="2.5" fill="#ffffff"/>
            <circle cx="61" cy="59" r="2.5" fill="#ffffff"/>
            <circle cx="39" cy="59" r="2.5" fill="#ffffff"/>
            <circle cx="36" cy="43" r="2.5" fill="#ffffff"/>
        </symbol>
    </svg>

    <!-- INICIO DE SESIÓN / REGISTRO COMPACTO -->
    <div id="auth-view" class="auth">
        <aside class="auth-visual">
            <div>
                <div style="margin-bottom: 20px;">
                    <img src="img/logo Denisse.png" alt="Denisse's Dream Garden Logo" style="width: 140px; height: auto; object-fit: contain; filter: drop-shadow(0px 4px 12px rgba(0,0,0,0.3));">
                </div>

                <div class="auth-lockup">
                    <span class="auth-lockup-name">Denisse's Dream Garden</span>
                </div>
                <h1 class="auth-headline">Conoce, cuida y aprende sobre la flora nativa de El Salvador.</h1>
                <p class="auth-tagline">Cultivando la identidad de nuestra tierra a través de la tecnología</p>
            </div>
            <span style="font-size: 0.88rem; opacity: 0.85; font-weight: 700;">INFRAMEN 2026</span>
        </aside>

        <main class="auth-panel">
            <div class="auth-card">
                <div class="auth-tabs">
                    <button type="button" id="tab-login" class="auth-tab active" onclick="showAuth('login')">Iniciar sesión</button>
                    <button type="button" id="tab-register" class="auth-tab" onclick="showAuth('register')">Crear cuenta</button>
                </div>

                <form id="form-login" autocomplete="off">
                    <h2 class="auth-title">¡Bienvenido de nuevo!</h2>
                    <p class="auth-sub">Ingresa para ver tu avance en el jardín botánico.</p>
                    <div class="field">
                        <label for="login-email">Correo electrónico</label>
                        <input type="email" id="login-email" required placeholder="tucorreo@ejemplo.com" autocomplete="none" value="" maxlength="35" oninput="validarCorreoInput(this, 'warn-login-email')">
                        <span id="warn-login-email" class="field-warn-msg">⚠️ Ingrese un correo electrónico válido sin secuencias aleatorias.</span>
                        <small style="font-size:0.75rem; color:#8c2347; margin-top:4px; display:block; font-weight:600;">* Permitido un máximo de 35 caracteres.</small>
                    </div>
                    <div class="field">
                        <label for="login-password">Contraseña</label>
                        <div class="password-wrapper">
                            <input type="password" id="login-password" required placeholder="Tu contraseña" autocomplete="new-password" value="" maxlength="20" oninput="validarPasswordMax(this)">
                            <button type="button" class="btn-toggle-pass" onclick="togglePasswordVisibility('login-password')">
                                <svg class="icon"><use href="#eye"/></svg>
                            </button>
                        </div>
                        <small style="font-size:0.75rem; color:#8c2347; margin-top:4px; display:block; font-weight:600;">* La contraseña permite un máximo de 20 caracteres.</small>
                    </div>
                    <p id="login-msg" class="form-msg"></p>
                    <button type="submit" class="btn-primary btn-block">Entrar al Jardín</button>
                </form>

                <form id="form-register" class="hidden" autocomplete="off">
                    <h2 class="auth-title">Crea tu cuenta</h2>
                    <p class="auth-sub">Guarda tu progreso y suma puntos en el quiz.</p>
                    <div class="field">
                        <label for="reg-name">Nombre completo</label>
                        <input type="text" id="reg-name" required placeholder="Tu nombre" value="" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                    </div>
                    <div class="field">
                        <label for="reg-email">Correo electrónico</label>
                        <input type="email" id="reg-email" required placeholder="tucorreo@ejemplo.com" value="" maxlength="35" oninput="validarCorreoInput(this, 'warn-reg-email')">
                        <span id="warn-reg-email" class="field-warn-msg">⚠️ Ingrese un correo electrónico válido sin secuencias aleatorias.</span>
                        <small style="font-size:0.75rem; color:#8c2347; margin-top:4px; display:block; font-weight:600;">* Permitido un máximo de 35 caracteres.</small>
                    </div>
                    <div class="field">
                        <label for="reg-pass">Contraseña</label>
                        <div class="password-wrapper">
                            <input type="password" id="reg-pass" required placeholder="Máximo 20 caracteres" autocomplete="new-password" value="" maxlength="20" oninput="validarPasswordMax(this)">
                            <button type="button" class="btn-toggle-pass" onclick="togglePasswordVisibility('reg-pass')">
                                <svg class="icon"><use href="#eye"/></svg>
                            </button>
                        </div>
                        <small style="font-size:0.75rem; color:#8c2347; margin-top:4px; display:block; font-weight:600;">* La contraseña permite un máximo de 20 caracteres.</small>
                    </div>
                    <p id="reg-msg" class="form-msg"></p>
                    <button type="submit" class="btn-primary btn-block">Registrarse gratis</button>
                </form>
            </div>
        </main>
    </div>

    <!-- PANTALLA PRINCIPAL DE LA APLICACIÓN -->
    <div id="app-view" class="hidden" data-tab="catalogo">

        <header class="topbar">
            <div class="topbar-inner">
                <!-- BOTÓN HAMBURGUESA PARA MÓVILES -->
                <button type="button" class="btn-hamburger" id="btn-hamburger" onclick="toggleMobileMenu(event)">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div class="brand" style="display: flex; align-items: center; gap: 12px;">
                    <img src="img/logo Denisse.png" alt="Logo" class="brand-logo-svg" style="width: 65px; height: 65px; object-fit: contain;">
                    <span class="brand-name" style="font-size: 1.35rem; font-weight: 800;">Denisse's Dream Garden</span>
                </div>

                <nav class="main-nav" id="main-nav">
                    <button class="nav-link active" onclick="switchTab('catalogo'); closeMobileMenu();">Catálogo</button>
                    <button class="nav-link" onclick="switchTab('quiz'); closeMobileMenu();">Quiz Botánico</button>
                    <button class="nav-link" onclick="switchTab('progreso'); closeMobileMenu();">Mi Progreso</button>
                    <button class="nav-link role-only docente-only admin-only hidden" id="nav-alumnos" onclick="switchTab('alumnos'); closeMobileMenu();">Progreso Alumnos</button>
                    <button class="nav-link role-only admin-only hidden" id="nav-admin" onclick="switchTab('admin'); closeMobileMenu();">Gestión Admin</button>
                </nav>

                <div class="user-menu">
                    <div class="user-trigger" onclick="toggleUserDropdown(event)">
                        <span id="user-avatar" class="user-avatar">U</span>
                        <span id="user-name-display">Usuario</span>
                        <span class="user-arrow">▾</span>
                    </div>
                    <div id="user-dropdown" class="user-dropdown">
                        <button class="dropdown-item" onclick="switchTab('progreso'); closeUserDropdown(); closeMobileMenu();">👤 Mi Progreso</button>
                        <div class="dropdown-divider"></div>
                        <button class="dropdown-item logout-btn" onclick="logout()">🚪 Cerrar Sesión</button>
                    </div>
                </div>
            </div>
        </header>

        <!-- HERO PRINCIPAL -->
        <section class="hero">
            <div class="hero-bg"></div>
            <div class="hero-inner">
                <h1>Descubre la Biodiversidad de El Salvador</h1>
                <p class="hero-sub">Especies nativas que llenan de vida nuestros bosques, cordilleras y ciudades.</p>
            </div>
        </section>

        <!-- SECCIÓN CATÁLOGO -->
        <section id="sec-catalogo" class="section active container">
            <div class="section-head">
                <div>
                    <h2 class="section-title">Catálogo de Especies</h2>
                    <p class="section-sub">Explora plantas emblemáticas ordenadas por región.</p>
                </div>
                <div class="region-selector">
                    <button class="btn-region active" onclick="filterRegion('todas')">Todas</button>
                    <button class="btn-region" onclick="filterRegion('central')">Central</button>
                    <button class="btn-region" onclick="filterRegion('occidental')">Occidente</button>
                    <button class="btn-region" onclick="filterRegion('oriental')">Oriente</button>
                </div>
            </div>
            <div class="plants-grid"></div>
        </section>

        <!-- SECCIÓN QUIZ -->
        <section id="sec-quiz" class="section container">
            <div class="user-welcome-card" style="margin-bottom: 32px; display:flex; justify-content:space-between; align-items:center;">
                <div class="user-welcome-info">
                    <span class="badge-level">
                        <svg class="badge-flower-icon"><use href="#icon-flower"/></svg> Desafío Botánico (5 pts por respuesta correcta)
                    </span>
                    <h2>Quiz de Conocimiento</h2>
                    <p class="progress-desc">Demuestra lo que sabes sobre la flora y flores nacionales de El Salvador.</p>
                </div>
                <div class="welcome-card-flower-decor" style="opacity: 0.9; margin-right: 15px;">
                    <svg style="width: 110px; height: 110px;"><use href="#icon-flower"/></svg>
                </div>
            </div>

            <div class="quiz-container-wrapper">
                <div class="quiz-card">
                    <div class="quiz-meta">
                        <span id="quiz-question-number">Pregunta 1 de 9</span>
                        <span class="score-badge">Puntuación: <span id="quiz-score">0</span> pts</span>
                    </div>
                    <div class="quiz-progress">
                        <div id="quiz-progress-bar" class="quiz-progress-bar" style="width: 10%;"></div>
                    </div>
                    <h3 id="quiz-question-text" class="quiz-question">Cargando pregunta...</h3>
                    <div id="quiz-options-container" class="quiz-options"></div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN MI PROGRESO -->
        <section id="sec-progreso" class="section container">
            <div class="progress-panel">
                <div class="user-welcome-card sec-progreso-card" style="margin-bottom:28px;">
                    <div class="user-welcome-info">
                        <span class="badge-level">
                            <svg class="badge-flower-icon"><use href="#icon-flower"/></svg> Nivel: Botánico Activo
                        </span>
                        <h2>¡Bienvenido, <span id="user-display-name">Usuario</span>!</h2>
                        <p class="progress-desc">
                            Avance guardado para: <strong id="user-display-email" style="color:var(--primary);">correo</strong> 
                            <span class="role-badge" id="user-display-rol">estudiante</span>
                        </p>
                    </div>
                    <div class="user-avatar-large">
                        <span id="user-avatar-big">U</span>
                    </div>
                </div>

                <div class="stats-grid">
                    <div class="stat-card stat-card-mint">
                        <div class="stat-card-top">
                            <div class="stat-icon">📊</div>
                            <span class="stat-pill">📈 Actividad Continua</span>
                        </div>
                        <div class="stat-info">
                            <span id="stat-quizzes" class="stat-number">0</span>
                            <span class="stat-label">Quizzes Completados</span>
                        </div>
                    </div>

                    <div class="stat-card stat-card-gold">
                        <div class="stat-card-top">
                            <div class="stat-icon">⭐</div>
                            <span class="stat-pill">🏆 Puntaje Acumulado</span>
                        </div>
                        <div class="stat-info">
                            <span id="stat-score" class="stat-number">0</span>
                            <span class="stat-label">Puntos Totales de Conocimiento</span>
                        </div>
                    </div>

                    <div class="stat-card stat-card-blossom">
                        <div class="stat-card-top">
                            <div class="stat-icon">🎯</div>
                            <span class="stat-pill">✨ Eficiencia Botánica</span>
                        </div>
                        <div class="stat-info">
                            <span id="stat-accuracy" class="stat-number">0%</span>
                            <span class="stat-label">Efectividad General</span>
                        </div>
                    </div>
                </div>

                <div id="chart-section-wrapper" class="chart-card-container" style="margin-top:24px; padding:24px; background:#ffffff; border-radius:20px; box-shadow:0 10px 25px rgba(27,67,50,0.05);">
                    <h3 style="margin-bottom:20px; color:var(--primary); font-family:var(--font-display); font-weight:800;">📊 Estadísticas de Aprendizaje Botánico</h3>
                    
                    <div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap:20px;">
                        <div style="background:#fff0f5; padding:20px; border-radius:16px; border:1px solid #f8b4d0; display:flex; flex-direction:column; align-items:center;">
                            <h4 style="margin:0 0 12px 0; color:#8c2347; font-family:'Plus Jakarta Sans', sans-serif;">Quizzes Completados</h4>
                            <div style="position:relative; width:160px; height:160px;">
                                <canvas id="chartQuizCanvas"></canvas>
                            </div>
                            <span id="lbl-quiz-pct" style="margin-top:12px; font-size:0.85rem; font-weight:700; color:#8c2347;">0% completado</span>
                        </div>

                        <div style="background:#e8f5e9; padding:20px; border-radius:16px; border:1px solid #a3e635; display:flex; flex-direction:column; align-items:center;">
                            <h4 style="margin:0 0 12px 0; color:#1b4332; font-family:'Plus Jakarta Sans', sans-serif;">Puntos Acumulados</h4>
                            <div style="position:relative; width:160px; height:160px;">
                                <canvas id="chartScoreCanvas"></canvas>
                            </div>
                            <span id="lbl-score-pct" style="margin-top:12px; font-size:0.85rem; font-weight:700; color:#1b4332;">0% del nivel</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECCIÓN PROGRESO ALUMNOS -->
        <section id="sec-alumnos" class="section container">
            <div class="user-welcome-card" style="margin-bottom:32px; display:flex; justify-content:space-between; align-items:center;">
                <div class="user-welcome-info">
                    <span class="badge-level">
                        <svg class="badge-flower-icon"><use href="#icon-flower"/></svg> Panel Académico
                    </span>
                    <h2>Seguimiento de Alumnos</h2>
                    <p class="progress-desc">Haz clic en la tarjeta de un estudiante para ver el historial y desglose de quizzes completados.</p>
                </div>
                <div class="welcome-card-flower-decor" style="opacity: 0.9; margin-right: 15px;">
                    <svg style="width: 110px; height: 110px;"><use href="#icon-flower"/></svg>
                </div>
            </div>
            <div id="alumnos-cards-container" class="alumnos-grid"></div>
        </section>

        <!-- SECCIÓN GESTIÓN ADMIN -->
        <section id="sec-admin" class="section container">
            <div class="user-welcome-card" style="margin-bottom:28px; display:flex; justify-content:space-between; align-items:center;">
                <div class="user-welcome-info">
                    <span class="badge-level">
                        <svg class="badge-flower-icon"><use href="#icon-flower"/></svg> Flora Nativa Salvadoreña
                    </span>
                    <h2>Panel de Administración General</h2>
                    <p class="progress-desc">Gestión de especies del catálogo, preguntas del Quiz y usuarios del sistema.</p>
                </div>
                <div class="welcome-card-flower-decor" style="opacity: 0.9; margin-right: 15px;">
                    <svg style="width: 110px; height: 110px;"><use href="#icon-flower"/></svg>
                </div>
            </div>

            <!-- MÓDULO PREGUNTAS EN QUIZ -->
            <div class="panel" style="margin-bottom:24px;">
                <h3 style="margin-bottom:15px; color:#1b4332; font-size:1.25rem;">❓ Preguntas Registradas en el Quiz</h3>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead>
                            <tr style="border-bottom:2px solid #e2e8f0; color:#1b4332;">
                                <th style="padding:10px;">#</th>
                                <th style="padding:10px;">Pregunta</th>
                                <th style="padding:10px;">Opción Correcta</th>
                                <th style="padding:10px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-admin-quiz">
                            <tr><td colspan="4" style="padding:10px;">Cargando preguntas del quiz...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel bloom-accent" style="margin-bottom:24px;">
                <h3 style="margin-bottom:16px; color:#1b4332; font-size:1.2rem;">📝 Agregar Nueva Pregunta al Quiz</h3>
                <form id="form-add-quiz-question" style="display:flex; flex-direction:column; gap:12px;">
                    <div>
                        <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:4px;">Pregunta:</label>
                        <input type="text" id="q-text" required placeholder="Ej: ¿En qué departamento se ubica el Parque Nacional El Imposible?" 
                               style="width:100%; height:40px; padding:0 12px; border:1px solid #ccc; border-radius:8px; font-size:0.88rem;" 
                               onkeydown="bloquearNumerosYSimbolos(event)" 
                               oninput="validarYBloquearTextoAzar(this)">
                    </div>

                    <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:12px;">
                        <div>
                            <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:4px;">Opción A:</label>
                            <input type="text" id="q-opt-a" required placeholder="Respuesta A" 
                                   style="width:100%; height:38px; padding:0 10px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" 
                                   onkeydown="bloquearNumerosYSimbolos(event)" 
                                   oninput="validarYBloquearTextoAzar(this)">
                        </div>
                        <div>
                            <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:4px;">Opción B:</label>
                            <input type="text" id="q-opt-b" required placeholder="Respuesta B" 
                                   style="width:100%; height:38px; padding:0 10px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" 
                                   onkeydown="bloquearNumerosYSimbolos(event)" 
                                   oninput="validarYBloquearTextoAzar(this)">
                        </div>
                        <div>
                            <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:4px;">Opción C:</label>
                            <input type="text" id="q-opt-c" required placeholder="Respuesta C" 
                                   style="width:100%; height:38px; padding:0 10px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" 
                                   onkeydown="bloquearNumerosYSimbolos(event)" 
                                   oninput="validarYBloquearTextoAzar(this)">
                        </div>
                    </div>
                    <div>
                        <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:4px;">Selecciona la Respuesta Correcta:</label>
                        <select id="q-correct-letter" style="width:100%; height:38px; padding:0 10px; border:1px solid #ccc; border-radius:8px; font-size:0.88rem; font-weight:700;">
                            <option value="A">Opción A</option>
                            <option value="B">Opción B</option>
                            <option value="C">Opción C</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary" style="height:42px; margin-top:6px;">+ Guardar Pregunta en BD</button>
                </form>
            </div>

            <div class="panel" id="exportable-plantas-container">
                <div class="panel-header-with-action">
                    <h3 style="color:#1b4332; font-size:1.25rem;">🌱 Plantas Registradas en el Catálogo</h3>
                    <div class="export-group">
                        <button class="btn-secondary" onclick="exportarPlantasPDF()">📄 Exportar PDF</button>
                        <button class="btn-secondary" onclick="exportarPlantasWord()">📝 Exportar Word</button>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; text-align:left;" id="tabla-exportable-plantas">
                        <thead>
                            <tr style="border-bottom:2px solid #e2e8f0; color:#1b4332;">
                                <th style="padding:10px;">ID</th>
                                <th style="padding:10px;">Nombre Común</th>
                                <th style="padding:10px;">Nombre Científico</th>
                                <th style="padding:10px;">Región</th>
                                <th style="padding:10px;" class="no-print">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-admin-plantas">
                            <tr><td colspan="5" style="padding:10px;">Cargando plantas...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- MÓDULO AGREGAR PLANTA -->
            <div class="panel bloom-accent" style="margin-top:24px;">
                <h3 style="margin-bottom:18px; color:#1b4332; font-size:1.2rem;">🌿 Agregar Nueva Planta al Catálogo</h3>
                <form id="form-add-planta" class="modal-grid-layout">
                    <div style="display:flex; flex-direction:column; gap:10px;">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                            <div>
                                <label style="font-size:0.78rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Nombre Común:</label>
                                <input type="text" id="p-nombre-comun" required placeholder="Ej: Chichipince" style="width:100%; height:36px; padding:0 8px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                            <div>
                                <label style="font-size:0.78rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Nombre Científico:</label>
                                <input type="text" id="p-nombre-cientifico" required placeholder="Ej: Hamelia patens" style="width:100%; height:36px; padding:0 8px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                            <div>
                                <label style="font-size:0.78rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Región:</label>
                                <select id="p-region" style="width:100%; height:36px; padding:0 6px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;">
                                    <option value="1">Central</option>
                                    <option value="2">Occidental</option>
                                    <option value="3">Oriental</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size:0.78rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Riego:</label>
                                <input type="text" id="p-riego" placeholder="Ej: Moderado" style="width:100%; height:36px; padding:0 8px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:10px;">
                            <div>
                                <label style="font-size:0.78rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Luz:</label>
                                <input type="text" id="p-luz" placeholder="Ej: Sol directo" style="width:100%; height:36px; padding:0 8px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                            <div>
                                <label style="font-size:0.78rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Suelo:</label>
                                <input type="text" id="p-suelo" placeholder="Ej: Suelo fértil" style="width:100%; height:36px; padding:0 8px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                        </div>

                        <div>
                            <label style="font-size:0.78rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Descripción / Características:</label>
                            <textarea id="p-cuidados" rows="3" placeholder="Detalles o descripción de la especie..." maxlength="250" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem; resize:none;" oninput="validarTextareaCuidados(this)"></textarea>
                            <span id="warn-p-cuidados" class="field-warn-msg">⚠️ Ingrese una descripción válida sin secuencias de caracteres o números aleatorios.</span>
                        </div>

                        <button type="submit" class="btn-primary" style="margin-top:6px; height:42px;">+ Guardar Planta en BD</button>
                    </div>

                    <div>
                        <label style="font-size:0.78rem; font-weight:700; color:#1b4332; display:block; margin-bottom:4px;">Imagen de la Planta:</label>
                        <label class="custom-file-upload">
                            <input type="file" id="p-file-input" accept="image/*" onchange="previewAddImage(event)" style="display:none;">
                            <span class="file-btn-badge">📷 Agregar imagen</span>
                            <span id="p-file-name" class="file-name-text">Sin archivo seleccionado</span>
                        </label>
                        <input type="hidden" id="p-imagen-url">
                        <div class="preview-container">
                            <button type="button" class="btn-remove-img" onclick="quitarImagenAdd()" title="Quitar imagen">✕</button>
                            <img id="p-preview-img" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100%' height='250' viewBox='0 0 400 250'><rect width='100%' height='100%' fill='%23fff0f5'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='16' fill='%238c2347'>🌱 Vista previa</text></svg>" alt="Vista previa">
                        </div>
                    </div>
                </form>
            </div>

            <div class="panel" style="margin-top:20px;">
                <h3 style="margin-bottom:15px; color:#1b4332; font-size:1.25rem;">Gestión de Roles y Eliminación de Usuarios</h3>
                <div style="overflow-x:auto;">
                    <table style="width:100%; border-collapse:collapse; text-align:left;">
                        <thead>
                            <tr style="border-bottom:2px solid #e2e8f0; color:#1b4332;">
                                <th style="padding:12px;">ID</th>
                                <th style="padding:12px;">Nombre</th>
                                <th style="padding:12px;">Correo</th>
                                <th style="padding:12px;">Rol Asignado</th>
                                <th style="padding:12px;">Cambiar Rol</th>
                                <th style="padding:12px;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla-admin-usuarios">
                            <tr><td colspan="6" style="padding:12px;">Cargando usuarios...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- MODAL VER DETALLE DE QUIZZES DEL ALUMNO SELECCIONADO -->
        <div id="modal-alumno-detail" class="modal-overlay">
            <div class="modal-card" style="max-width: 580px;">
                <button type="button" class="modal-close" onclick="closeAlumnoModal()">✕</button>
                <div style="display:flex; align-items:center; gap:16px; margin-bottom:18px;">
                    <div id="modal-alumno-avatar" class="alumno-avatar">A</div>
                    <div>
                        <h2 id="modal-alumno-nombre" style="color:var(--primary); font-size:1.4rem; font-weight:800;">Nombre Alumno</h2>
                        <span id="modal-alumno-email" style="color:var(--text-muted); font-size:0.88rem;">correo@ejemplo.com</span>
                    </div>
                </div>

                <div class="alumno-stats" style="margin-bottom:20px;">
                    <div class="alumno-stat-item">
                        <span id="modal-alumno-total-quizzes" class="stat-val">0</span>
                        <span class="stat-lbl">Quizzes Intentados</span>
                    </div>
                    <div class="alumno-stat-item">
                        <span id="modal-alumno-total-pts" class="stat-val score-val">0 pts</span>
                        <span class="stat-lbl">Puntaje Total Acumulado</span>
                    </div>
                </div>

                <h3 style="color:var(--primary); font-size:1.1rem; margin-bottom:10px;">📋 Historial de Quizzes Realizados</h3>
                <div id="modal-alumno-quiz-list" class="quiz-detail-list">
                    <p style="font-size:0.88rem; color:var(--text-muted);">Cargando historial...</p>
                </div>
            </div>
        </div>

        <!-- MODAL EDITAR PREGUNTA -->
        <div id="modal-edit-quiz" class="modal-overlay">
            <div class="modal-card" style="max-width: 550px;">
                <button type="button" class="modal-close" onclick="closeEditQuizModal()">✕</button>
                <h2 style="color:#1b4332; margin-bottom:16px; font-size:1.3rem; font-weight:700;">Editar Pregunta del Quiz</h2>
                <form id="form-edit-quiz-question" style="display:flex; flex-direction:column; gap:12px;">
                    <input type="hidden" id="edit-q-id">
                    <div>
                        <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:4px;">Enunciado de la Pregunta:</label>
                        <input type="text" id="edit-q-text" required style="width:100%; height:40px; padding:0 12px; border:1px solid #ccc; border-radius:8px; font-size:0.88rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                    </div>
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <div>
                            <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Opción A:</label>
                            <input type="text" id="edit-q-opt-a" required style="width:100%; height:36px; padding:0 10px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                        </div>
                        <div>
                            <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Opción B:</label>
                            <input type="text" id="edit-q-opt-b" required style="width:100%; height:36px; padding:0 10px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                        </div>
                        <div>
                            <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Opción C:</label>
                            <input type="text" id="edit-q-opt-c" required style="width:100%; height:36px; padding:0 10px; border:1px solid #ccc; border-radius:8px; font-size:0.85rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                        </div>
                    </div>
                    <div>
                        <label style="font-size:0.8rem; font-weight:700; color:#1b4332; display:block; margin-bottom:4px;">Respuesta Correcta:</label>
                        <select id="edit-q-correct-letter" style="width:100%; height:38px; padding:0 10px; border:1px solid #ccc; border-radius:8px; font-size:0.88rem; font-weight:700;">
                            <option value="A">Opción A</option>
                            <option value="B">Opción B</option>
                            <option value="C">Opción C</option>
                        </select>
                    </div>
                    <button type="submit" class="btn-primary" style="height:42px; margin-top:8px;">Guardar Cambios</button>
                </form>
            </div>
        </div>

        <!-- MODAL EDITAR PLANTA -->
        <div id="modal-edit-plant" class="modal-overlay">
            <div class="modal-card">
                <button type="button" class="modal-close" onclick="closeEditModal()">✕</button>
                <h2 style="color:#1b4332; margin-bottom:14px; font-size:1.3rem; font-weight:700;">Editar Planta</h2>
                <form id="form-edit-planta" class="modal-grid-layout">
                    <input type="hidden" id="edit-p-id">
                    <div style="display:flex; flex-direction:column; gap:8px;">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                            <div>
                                <label style="font-size:0.75rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Nombre Común:</label>
                                <input type="text" id="edit-p-nombre-comun" required style="width:100%; height:34px; padding:0 8px; border:1px solid #ccc; border-radius:6px; font-size:0.82rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                            <div>
                                <label style="font-size:0.75rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Nombre Científico:</label>
                                <input type="text" id="edit-p-nombre-cientifico" required style="width:100%; height:34px; padding:0 8px; border:1px solid #ccc; border-radius:6px; font-size:0.82rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                            <div>
                                <label style="font-size:0.75rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Región:</label>
                                <select id="edit-p-region" style="width:100%; height:34px; padding:0 6px; border:1px solid #ccc; border-radius:6px; font-size:0.82rem;">
                                    <option value="1">Central</option>
                                    <option value="2">Occidental</option>
                                    <option value="3">Oriental</option>
                                </select>
                            </div>
                            <div>
                                <label style="font-size:0.75rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Riego:</label>
                                <input type="text" id="edit-p-riego" style="width:100%; height:34px; padding:0 8px; border:1px solid #ccc; border-radius:6px; font-size:0.82rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                        </div>

                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:8px;">
                            <div>
                                <label style="font-size:0.75rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Luz:</label>
                                <input type="text" id="edit-p-luz" style="width:100%; height:34px; padding:0 8px; border:1px solid #ccc; border-radius:6px; font-size:0.82rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                            <div>
                                <label style="font-size:0.75rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Suelo:</label>
                                <input type="text" id="edit-p-suelo" style="width:100%; height:34px; padding:0 8px; border:1px solid #ccc; border-radius:6px; font-size:0.82rem;" onkeydown="bloquearNumerosYSimbolos(event)" oninput="validarYBloquearTextoAzar(this)">
                            </div>
                        </div>

                        <div>
                            <label style="font-size:0.75rem; font-weight:700; color:#1b4332; display:block; margin-bottom:2px;">Descripción / Características:</label>
                            <textarea id="edit-p-cuidados" rows="3" style="width:100%; padding:6px; border:1px solid #ccc; border-radius:6px; font-size:0.82rem; resize:none;" oninput="validarYBloquearTextoAzar(this)"></textarea>
                        </div>

                        <button type="submit" class="btn-primary" style="margin-top:6px; height:40px; font-size:0.88rem;">Guardar Cambios</button>
                    </div>

                    <div>
                        <label style="font-size:0.75rem; font-weight:700; color:#1b4332; display:block; margin-bottom:4px;">Imagen Local:</label>
                        <label class="custom-file-upload">
                            <input type="file" id="edit-p-file-input" accept="image/*" onchange="previewEditImage(event)" style="display:none;">
                            <span class="file-btn-badge">📷 Agregar imagen</span>
                            <span id="edit-p-file-name" class="file-name-text">Seleccionar archivo...</span>
                        </label>
                        <input type="hidden" id="edit-p-imagen">
                        
                        <div class="preview-container">
                            <button type="button" class="btn-remove-img" onclick="quitarImagenEdit()" title="Quitar imagen">✕</button>
                            <img id="edit-p-preview-img" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100%' height='250' viewBox='0 0 400 250'><rect width='100%' height='100%' fill='%23fff0f5'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='16' fill='%238c2347'>🌱 Vista previa</text></svg>" alt="Vista previa">
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- MODAL FICHA TÉCNICA -->
        <div id="modal-plant" class="modal-overlay">
            <div class="modal-card">
                <button type="button" class="modal-close" onclick="closeModal()">✕</button>
                <div class="modal-grid-layout">
                    <div class="modal-body-content">
                        <h2 id="modal-title" class="modal-title">Planta</h2>
                        <p class="modal-scientific-box"><strong>Nombre científico:</strong> <span id="modal-latin"></span></p>
                        
                        <div class="modal-details-list">
                            <p><strong>💧 Riego:</strong> <span id="modal-riego"></span></p>
                            <p><strong>☀️ Luz:</strong> <span id="modal-luz"></span></p>
                            <p><strong>🌱 Suelo:</strong> <span id="modal-suelo"></span></p>
                        </div>
                        
                        <div class="modal-care-box">💡 <strong>Descripción / Características:</strong> <span id="modal-cuidados"></span></div>
                    </div>

                    <div class="side-image-container">
                        <img id="modal-img" src="data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100%' height='250' viewBox='0 0 400 250'><rect width='100%' height='100%' fill='%23fff0f5'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='16' fill='%238c2347'>🌱 Vista previa</text></svg>" alt="Planta">
                    </div>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <footer>
            <div class="footer-content">
                <div class="footer-col-main">
                    <div class="footer-brand-title">
                        <span>Denisse's Dream Garden</span>
                    </div>
                    <p class="footer-brand-desc" style="max-width: 480px; margin-top: 8px;">
                        Plataforma interactiva para la divulgación, aprendizaje y conservación de la biodiversidad vegetal nativa de El Salvador.
                    </p>
                </div>

                <div>
                    <h4 class="footer-col-title">Proyecto Educativo</h4>
                    <p style="font-size: 0.88rem; color: #fdebf3; margin-bottom: 14px; line-height: 1.5; max-width: 420px;">
                        Iniciativa pedagógica orientada al fomento del cuidado ambiental y la botánica nacional.
                    </p>
                    <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                        <span class="footer-badge">INFRAMEN 2026</span>
                        <a href="https://www.instagram.com/dream_gaarden?utm_source=qr&cplk=MWV6NDNyaXcyZW9zdQ==" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 8px; background: rgba(255, 255, 255, 0.12); color: #ffffff; padding: 6px 14px; border-radius: 20px; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: 1px solid rgba(255, 255, 255, 0.25); transition: all 0.3s ease;">
                            <img src="img/insta.jpg" alt="Instagram" style="width: 20px; height: 20px; border-radius: 4px; object-fit: cover;">
                            <span>@dream_gaarden</span>
                        </a>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- SCRIPT JAVASCRIPT CON CORRECCIÓN DE PERSISTENCIA Y MENÚ HAMBURGUESA -->
    <script>
        const DEFAULT_SVG_PREVIEW = "data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='100%' height='250' viewBox='0 0 400 250'><rect width='100%' height='100%' fill='%23fff0f5'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='16' fill='%238c2347'>🌱 Vista previa</text></svg>";

        function toggleMobileMenu(e) {
            if (e) e.stopPropagation();
            const nav = document.getElementById('main-nav');
            const btn = document.getElementById('btn-hamburger');
            
            if (nav && btn) {
                nav.classList.toggle('show');
                btn.classList.toggle('active');
            }
        }

        function closeMobileMenu() {
            const nav = document.getElementById('main-nav');
            const btn = document.getElementById('btn-hamburger');
            
            if (nav && btn) {
                nav.classList.remove('show');
                btn.classList.remove('active');
            }
        }

        window.addEventListener('click', (e) => {
            const nav = document.getElementById('main-nav');
            const btn = document.getElementById('btn-hamburger');
            
            if (nav && nav.classList.contains('show')) {
                if (!nav.contains(e.target) && !btn.contains(e.target)) {
                    closeMobileMenu();
                }
            }
        });

        function quitarImagenEdit() {
            document.getElementById('edit-p-imagen').value = '';
            document.getElementById('edit-p-preview-img').src = DEFAULT_SVG_PREVIEW;
            document.getElementById('edit-p-file-name').innerText = 'Sin archivo / Eliminada';
            document.getElementById('edit-p-file-input').value = '';
        }

        function quitarImagenAdd() {
            document.getElementById('p-imagen-url').value = '';
            document.getElementById('p-preview-img').src = DEFAULT_SVG_PREVIEW;
            document.getElementById('p-file-name').innerText = 'Sin archivo seleccionado';
            document.getElementById('p-file-input').value = '';
        }

        function validarCorreoInput(input, warnElementId) {
            if (input.value.length > 35) {
                input.value = input.value.slice(0, 35);
            }

            const warnMsg = document.getElementById(warnElementId);
            const texto = input.value.toLowerCase();

            const patronesAzar = /[bcdfghjklmnñpqrstvwxyz]{5,}|(.)\1{3,}|sdfgh|dfghj|fghjk|ghjkl|qwerty|zxcvb|asdfg|hjkl|uyyt|ftft/i;

            if (patronesAzar.test(texto)) {
                if (warnMsg) warnMsg.classList.add('active');
                input.value = '';
                input.setCustomValidity("Ingresa un correo electrónico válido sin secuencias aleatorias.");
            } else {
                if (warnMsg) warnMsg.classList.remove('active');
                
                const regexEmail = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (input.value.length > 0 && !regexEmail.test(input.value)) {
                    if (!input.value.includes('@')) {
                        input.setCustomValidity("Incluye un '@' en la dirección de correo.");
                    } else {
                        input.setCustomValidity("Ingresa un dominio válido (ej: .com).");
                    }
                } else {
                    input.setCustomValidity("");
                }
            }
        }

        function bloquearNumerosYSimbolos(e) {
            const teclasControl = ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End', 'Enter'];
            if (teclasControl.includes(e.key) || e.ctrlKey || e.metaKey) {
                return;
            }

            const esValido = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s¿?]$/;
            if (!esValido.test(e.key)) {
                e.preventDefault();
            }
        }

        function esTextoIlegibleOAzar(texto) {
            if (!texto || texto.trim().length === 0) return false;
            
            const textoLower = texto.toLowerCase().trim();

            if (/[bcdfghjklmnñpqrstvwxyz]{5,}/i.test(textoLower)) {
                return true;
            }

            if (/[0-9]{6,}/i.test(textoLower)) {
                return true;
            }

            if (/(.)\1{3,}/i.test(textoLower)) {
                return true;
            }

            const patronesTeclado = /sdfgh|dfghj|fghjk|ghjkl|hjkl|asdfg|qwerty|zxcvb|fdgsh|fsghaj|jklñ|dfgg|hhjj|kkll|ksks|sjsj|hgff|fhfh|ghgh|shjd|jgyi|sjjd|fgdd|ugug|ijmk|ugnk/i;
            if (patronesTeclado.test(textoLower)) {
                return true;
            }

            return false;
        }

        function validarYBloquearTextoAzar(input) {
            let textoLimpio = input.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s¿?]/g, '');

            if (esTextoIlegibleOAzar(textoLimpio)) {
                input.value = '';
            } else {
                input.value = textoLimpio;
            }
        }

        function validarTextareaCuidados(textarea) {
            const warnMsg = document.getElementById('warn-p-cuidados');
            const val = textarea.value;

            if (esTextoIlegibleOAzar(val)) {
                textarea.value = '';
                if (warnMsg) warnMsg.classList.add('active');
            } else {
                if (warnMsg) warnMsg.classList.remove('active');
            }
        }

        function validarPasswordMax(input) {
            if (input.value.length >= 20) {
                input.setCustomValidity("La contraseña solo permite un máximo de 20 caracteres.");
            } else {
                input.setCustomValidity("");
            }
        }

        const API_REGISTRO = 'api/registro.php';
        const API_QUIZ = 'api/quiz.php';
        const API_PLANTAS = 'api/plantas.php';

        let currentUser = null;
        let listaPlantas = [];
        let listaAlumnosGlobal = [];
        let quizQuestions = [];
        let currentQuestionIndex = 0;
        let quizScore = 0;
        let isAnswered = false;

        let chartQuizInstance = null;
        let chartScoreInstance = null;

        window.addEventListener('DOMContentLoaded', () => {
            // Recuperar sesión si existe al recargar
            const usuarioGuardado = sessionStorage.getItem('usuario_logueado');
            if (usuarioGuardado) {
                currentUser = JSON.parse(usuarioGuardado);
                enterApp();
            }

            const loginEmail = document.getElementById('login-email');
            const loginPass = document.getElementById('login-password');
            if (loginEmail) loginEmail.value = '';
            if (loginPass) loginPass.value = '';
        });

        function togglePasswordVisibility(inputId) {
            const input = document.getElementById(inputId);
            if (input) input.type = input.type === 'password' ? 'text' : 'password';
        }

        async function apiPost(url, body) {
            try {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(body)
                });
                
                const text = await res.text();

                try {
                    return JSON.parse(text);
                } catch (jsonErr) {
                    console.error("Error al parsear JSON. Respuesta del servidor:", text);
                    return { success: false, message: "Error PHP: " + text.substring(0, 120) };
                }
            } catch (err) {
                console.error("Error de red o ruta:", err);
                return { success: false, message: "No se pudo conectar con el servidor." };
            }
        }

        async function exportarPlantasPDF() {
            const originalElement = document.getElementById('exportable-plantas-container');
            if (!originalElement) return;

            const clone = originalElement.cloneNode(true);

            const buttonsInClone = clone.querySelectorAll('.no-print, .export-group');
            buttonsInClone.forEach(b => b.style.display = 'none');

            const actionHeaders = clone.querySelectorAll('th:nth-child(5), td:nth-child(5)');
            actionHeaders.forEach(el => el.style.display = 'none');

            clone.style.position = 'absolute';
            clone.style.top = '-9999px';
            clone.style.left = '-9999px';
            clone.style.width = originalElement.offsetWidth + 'px';
            document.body.appendChild(clone);

            try {
                const canvas = await html2canvas(clone, {
                    scale: 2,
                    useCORS: true,
                    backgroundColor: '#ffffff'
                });

                const imgData = canvas.toDataURL('image/png');
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                const imgWidth = 190;
                const imgHeight = (canvas.height * imgWidth) / canvas.width;

                pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);
                pdf.save('Plantas_Registradas_Catalogo.pdf');
            } catch (err) {
                alert('Ocurrió un error al generar el PDF.');
            } finally {
                document.body.removeChild(clone);
            }
        }

        function exportarPlantasWord() {
            if (!listaPlantas || listaPlantas.length === 0) {
                alert("No hay plantas registradas para exportar.");
                return;
            }

            let tableRows = '';
            listaPlantas.forEach((p, idx) => {
                const bgRow = idx % 2 === 0 ? '#ffffff' : '#fdf5f8';
                tableRows += `
                    <tr style="background-color: ${bgRow};">
                        <td style="padding:10px; border:1px solid #dcdcdc; text-align:center; font-weight:bold; color:#1b4332; width:10%;">#${p.id_planta}</td>
                        <td style="padding:10px; border:1px solid #dcdcdc; color:#1b4332; width:35%;"><strong>${p.nombre_comun}</strong></td>
                        <td style="padding:10px; border:1px solid #dcdcdc; color:#40916c; width:35%;"><i>${p.nombre_cientifico}</i></td>
                        <td style="padding:10px; border:1px solid #dcdcdc; color:#1b4332; width:20%;">${p.region || 'N/A'}</td>
                    </tr>`;
            });

            const html = `
                <html xmlns:o='urn:schemas-microsoft-com:office:office' xmlns:w='urn:schemas-microsoft-com:office:word' xmlns='http://www.w3.org/TR/REC-html40'>
                <head>
                    <meta charset="utf-8">
                    <title>Catálogo de Plantas - Denisse's Dream Garden</title>
                    <style>
                        @page { size: portrait; margin: 1.5cm; }
                        body { font-family: 'Arial', sans-serif; color: #1b4332; margin: 0; padding: 0; }
                        .header-title { font-size: 20pt; font-weight: bold; color: #1b4332; margin-bottom: 2pt; }
                        .subtitle { color: #8c2347; font-size: 11pt; margin-top: 0; margin-bottom: 20pt; font-weight: bold; }
                        table { width: 100%; border-collapse: collapse; margin-top: 10px; table-layout: fixed; }
                        th { background-color: #1b4332; color: #ffffff; padding: 10px; border: 1px solid #1b4332; text-align: left; font-size: 11pt; }
                        td { font-size: 10pt; word-wrap: break-word; }
                    </style>
                </head>
                <body>
                    <div class="header-title">Denisse's Dream Garden</div>
                    <div class="subtitle">Catálogo Oficial de Plantas Registradas - INFRAMEN 2026</div>
                    <table>
                        <thead>
                            <tr>
                                <th style="width: 10%; text-align: center;">ID</th>
                                <th style="width: 35%;">Nombre Común</th>
                                <th style="width: 35%;">Nombre Científico</th>
                                <th style="width: 20%;">Región</th>
                            </tr>
                        </thead>
                        <tbody>${tableRows}</tbody>
                    </table>
                </body>
                </html>`;

            const blob = new Blob(['\ufeff', html], { type: 'application/msword' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `Plantas_Registradas_Catalogo.doc`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        }

        async function cargarProgresoUsuario() {
            if (!currentUser || !currentUser.id_usuario) return;

            try {
                const res = await fetch(`${API_REGISTRO}?action=get_user_stats&id_usuario=${currentUser.id_usuario}&nocache=${new Date().getTime()}`);
                const data = await res.json();

                if (data.success && data.stats) {
                    const serverQuizzes = parseInt(data.stats.quizzes) || 0;
                    const serverScore = parseInt(data.stats.score) || 0;

                    const localQuizzes = (currentUser.stats && currentUser.stats.quizzes) ? parseInt(currentUser.stats.quizzes) : 0;
                    const localScore = (currentUser.stats && currentUser.stats.score) ? parseInt(currentUser.stats.score) : 0;

                    currentUser.stats = {
                        quizzes: Math.max(serverQuizzes, localQuizzes),
                        score: Math.max(serverScore, localScore)
                    };

                    sessionStorage.setItem('user_stats_' + currentUser.id_usuario, JSON.stringify(currentUser.stats));
                }
            } catch (err) {
                console.warn("Manteniendo avance local:", err);
                const localSaved = sessionStorage.getItem('user_stats_' + currentUser.id_usuario);
                if (localSaved) {
                    currentUser.stats = JSON.parse(localSaved);
                }
            }

            updateStatsDisplay();
        }

        function updateStatsDisplay() {
            if (!currentUser) return;

            const quizzesVal = (currentUser.stats && currentUser.stats.quizzes !== undefined) 
                                ? parseInt(currentUser.stats.quizzes) 
                                : 0;
                                
            const scoreVal = (currentUser.stats && currentUser.stats.score !== undefined) 
                              ? parseInt(currentUser.stats.score) 
                              : 0;

            let accuracyVal = '0%';
            if (quizzesVal > 0 && scoreVal > 0) {
                const maxPosible = quizzesVal * 45; 
                const pct = Math.min(Math.round((scoreVal / maxPosible) * 100), 100);
                accuracyVal = `${pct}%`;
            }

            const elQuizzes = document.getElementById('stat-quizzes');
            const elScore = document.getElementById('stat-score');
            const elAccuracy = document.getElementById('stat-accuracy');

            if (elQuizzes) elQuizzes.innerText = quizzesVal;
            if (elScore) elScore.innerText = scoreVal;
            if (elAccuracy) elAccuracy.innerText = accuracyVal;

            initChart();
        }

        function initChart() {
            const wrapper = document.getElementById('chart-section-wrapper');
            if (wrapper) wrapper.classList.remove('hidden');

            const quizzes = (currentUser && currentUser.stats && currentUser.stats.quizzes !== undefined) 
                            ? parseInt(currentUser.stats.quizzes) 
                            : 0;
                            
            const score = (currentUser && currentUser.stats && currentUser.stats.score !== undefined) 
                          ? parseInt(currentUser.stats.score) 
                          : 0;

            const META_QUIZZES = 10;
            const META_PUNTOS = 50;

            const quizPercent = Math.min(Math.round((quizzes / META_QUIZZES) * 100), 100);
            const scorePercent = Math.min(Math.round((score / META_PUNTOS) * 100), 100);

            document.getElementById('lbl-quiz-pct').innerText = `${quizPercent}% completado`;
            document.getElementById('lbl-score-pct').innerText = `${scorePercent}% del nivel`;

            const centerTextPlugin = {
                id: 'centerTextPlugin',
                beforeDraw(chart) {
                    const { width, height, ctx } = chart;
                    const text = chart.config.options.plugins.centerTextVal || '';
                    ctx.save();
                    ctx.font = 'bold 22px "Plus Jakarta Sans", sans-serif';
                    ctx.fillStyle = chart.config.options.plugins.centerTextColor || '#1b4332';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(text, width / 2, height / 2);
                    ctx.restore();
                }
            };

            const ctxQuiz = document.getElementById('chartQuizCanvas');
            if (ctxQuiz) {
                if (chartQuizInstance) chartQuizInstance.destroy();
                chartQuizInstance = new Chart(ctxQuiz, {
                    type: 'doughnut',
                    data: {
                        labels: ['Completado', 'Restante'],
                        datasets: [{
                            data: [quizPercent, 100 - quizPercent],
                            backgroundColor: ['#f38cb7', '#fce4ec'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '78%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false },
                            centerTextVal: `${quizzes}`,
                            centerTextColor: '#8c2347'
                        }
                    },
                    plugins: [centerTextPlugin]
                });
            }

            const ctxScore = document.getElementById('chartScoreCanvas');
            if (ctxScore) {
                if (chartScoreInstance) chartScoreInstance.destroy();
                chartScoreInstance = new Chart(ctxScore, {
                    type: 'doughnut',
                    data: {
                        labels: ['Conseguido', 'Restante'],
                        datasets: [{
                            data: [scorePercent, 100 - scorePercent],
                            backgroundColor: ['#2d6a4f', '#c8e6c9'],
                            borderWidth: 0
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '78%',
                        plugins: {
                            legend: { display: false },
                            tooltip: { enabled: false },
                            centerTextVal: `${score} pts`,
                            centerTextColor: '#1b4332'
                        }
                    },
                    plugins: [centerTextPlugin]
                });
            }
        }

        async function cargarPreguntasQuizBD() {
            try {
                const res = await fetch(API_QUIZ + '?action=get_preguntas&nocache=' + new Date().getTime());
                const data = await res.json();
                
                if (data.success && Array.isArray(data.preguntas) && data.preguntas.length > 0) {
                    quizQuestions = data.preguntas;
                } else {
                    throw new Error("Respuesta de API sin preguntas");
                }
            } catch (err) {
                // Fallback local si falla la BD
                quizQuestions = [
                    {
                        id: 1,
                        question: "¿Cuál es el árbol nacional de El Salvador?",
                        options: [
                            { id: 1, letter: "A", text: "Maquilíshuat", correct: true },
                            { id: 2, letter: "B", text: "Bálsamo", correct: false },
                            { id: 3, letter: "C", text: "Conacaste", correct: false }
                        ]
                    }
                ];
            }

            currentQuestionIndex = 0;
            quizScore = 0;
            loadQuestion();
            
            if (typeof renderizarTablaQuizAdmin === 'function') {
                renderizarTablaQuizAdmin();
            }
        }

        function loadQuestion() {
            isAnswered = false;
            if (!quizQuestions || quizQuestions.length === 0) return;

            const q = quizQuestions[currentQuestionIndex];
            
            document.getElementById('quiz-question-number').innerText = `Pregunta ${currentQuestionIndex + 1} de ${quizQuestions.length}`;
            document.getElementById('quiz-score').innerText = quizScore;
            document.getElementById('quiz-progress-bar').style.width = `${((currentQuestionIndex + 1) / quizQuestions.length) * 100}%`;
            document.getElementById('quiz-question-text').innerText = q.question || q.enunciado;

            const container = document.getElementById('quiz-options-container');
            container.innerHTML = '';

            const opciones = q.options || q.respuestas || [];

            if (opciones.length === 0) {
                container.innerHTML = `<p style="color:var(--text-muted); font-size:0.9rem;">No hay respuestas registradas para esta pregunta.</p>`;
                return;
            }

            const letras = ['A', 'B', 'C', 'D'];

            opciones.forEach((opt, idx) => {
                const btn = document.createElement('button');
                btn.className = 'quiz-option';
                
                const esCorrecta = (opt.correct !== undefined) ? opt.correct : (opt.es_correcta == 1);
                btn.onclick = () => handleAnswer(btn, esCorrecta);
                
                const letra = opt.letter || letras[idx] || '•';
                const texto = opt.text || opt.texto_respuesta || '';

                btn.innerHTML = `<span class="opt-letter">${letra}</span> ${texto}`;
                container.appendChild(btn);
            });
        }

        function handleAnswer(selectedBtn, isCorrect) {
            if (isAnswered) return;
            isAnswered = true;

            if (isCorrect) {
                selectedBtn.classList.add('correct');
                quizScore += 5;
                document.getElementById('quiz-score').innerText = quizScore;
            } else {
                selectedBtn.classList.add('incorrect');
            }

            setTimeout(() => {
                currentQuestionIndex++;
                if (currentQuestionIndex < quizQuestions.length) {
                    loadQuestion();
                } else {
                    showQuizResult();
                }
            }, 1000);
        }

        async function showQuizResult() {
            const container = document.getElementById('quiz-options-container');
            document.getElementById('quiz-question-text').innerText = "¡Quiz completado!";
            container.innerHTML = `
                <div style="text-align: center; padding: 20px 0;">
                    <p style="font-size: 1.2rem; color: #15803d; margin-bottom: 20px;">
                        Obtuviste una puntuación total de <strong>${quizScore} puntos</strong>.
                    </p>
                    <button class="btn-primary" onclick="restartQuiz()">Reiniciar Quiz 🔄</button>
                </div>
            `;

            if (currentUser) {
                if (!currentUser.stats) {
                    currentUser.stats = { quizzes: 0, score: 0 };
                }

                currentUser.stats.quizzes = (parseInt(currentUser.stats.quizzes) || 0) + 1;
                currentUser.stats.score = (parseInt(currentUser.stats.score) || 0) + quizScore;

                sessionStorage.setItem('user_stats_' + currentUser.id_usuario, JSON.stringify(currentUser.stats));

                updateStatsDisplay();

                await apiPost(API_QUIZ, {
                    id_usuario: currentUser.id_usuario,
                    puntaje: quizScore,
                    total_preguntas: quizQuestions.length
                });
            }
        }

        function restartQuiz() {
            currentQuestionIndex = 0;
            quizScore = 0;
            loadQuestion();
        }

        async function cargarPlantasBD() {
            try {
                const res = await fetch(API_PLANTAS + '?nocache=' + new Date().getTime());
                const data = await res.json();
                if (data.success && Array.isArray(data.plantas)) {
                    listaPlantas = data.plantas;
                    renderizarPlantas('todas');
                    renderizarTablaPlantasAdmin();
                }
            } catch (err) { 
                console.error("Error al conectar con la API de plantas:", err); 
            }
        }

        function renderizarPlantas(regionFiltro) {
            const container = document.querySelector('.plants-grid');
            if (!container) return;
            container.innerHTML = '';

            if (!listaPlantas || listaPlantas.length === 0) {
                container.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: var(--text-muted); font-weight: 700;">No hay plantas registradas en esta sección.</p>`;
                return;
            }

            const filtro = regionFiltro.toLowerCase();

            listaPlantas.forEach(p => {
                const regionNombre = (p.region ? p.region : 'central').toLowerCase();
                
                const coincide = (filtro === 'todas') ||
                    (filtro === 'central' && regionNombre.includes('central')) ||
                    ((filtro === 'occidente' || filtro === 'occidental') && regionNombre.includes('occident')) ||
                    ((filtro === 'oriente' || filtro === 'oriental') && regionNombre.includes('orient'));

                if (coincide) {
                    const img = (p.imagen_url && p.imagen_url.trim() !== '') ? p.imagen_url : DEFAULT_SVG_PREVIEW;
                    container.innerHTML += `
                        <article class="plant" onclick="openModal(${p.id_planta})">
                            <div class="plant-photo">
                                <span class="plant-chip"><span class="region-dot"></span> ${p.region || 'Central'}</span>
                                <img src="${img}" alt="${p.nombre_comun}" onerror="this.onerror=null; this.src='${DEFAULT_SVG_PREVIEW}';">
                            </div>
                            <div class="plant-body">
                                <div>
                                    <h3 class="plant-name">${p.nombre_comun}</h3>
                                    <p class="plant-latin">${p.nombre_cientifico}</p>
                                    <p class="plant-desc">${p.cuidados || 'Sin observaciones.'}</p>
                                </div>
                                <button class="plant-toggle-bar">Ver ficha técnica ➔</button>
                            </div>
                        </article>
                    `;
                }
            });
        }

        function renderizarTablaPlantasAdmin() {
            const tbody = document.getElementById('tabla-admin-plantas');
            if (!tbody) return;
            tbody.innerHTML = '';

            if (!listaPlantas || listaPlantas.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="padding:10px; text-align:center;">No hay plantas registradas.</td></tr>`;
                return;
            }

            listaPlantas.forEach(p => {
                tbody.innerHTML += `
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:10px;"><strong>#${p.id_planta}</strong></td>
                        <td style="padding:10px;">${p.nombre_comun}</td>
                        <td style="padding:10px;"><em>${p.nombre_cientifico}</em></td>
                        <td style="padding:10px;">${p.region || 'Central'}</td>
                        <td style="padding:10px;" class="no-print">
                            <div class="action-btn-group">
                                <button class="btn-edit-admin" onclick="openEditModal(${p.id_planta})">✏️ Editar</button>
                                <button class="btn-delete-admin" onclick="eliminarPlanta(${p.id_planta})">🗑️ Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        function renderizarTablaQuizAdmin() {
            const tbody = document.getElementById('tabla-admin-quiz');
            if (!tbody) return;
            tbody.innerHTML = '';

            quizQuestions.forEach((q, idx) => {
                const opciones = q.options || q.respuestas || [];
                const correctOpt = opciones.find(o => o.correct || o.es_correcta == 1);
                tbody.innerHTML += `
                    <tr style="border-bottom:1px solid #eee;">
                        <td style="padding:10px;"><strong>#${idx + 1}</strong></td>
                        <td style="padding:10px;">${q.question || q.enunciado}</td>
                        <td style="padding:10px;"><strong style="color:var(--emerald);">${correctOpt ? (correctOpt.letter || '') + ': ' + (correctOpt.text || correctOpt.texto_respuesta) : 'N/A'}</strong></td>
                        <td style="padding:10px;">
                            <div class="action-btn-group">
                                <button class="btn-edit-admin" onclick="openEditQuizModal(${q.id})">✏️ Editar</button>
                                <button class="btn-delete-admin" onclick="eliminarPreguntaQuiz(${q.id})">🗑️ Eliminar</button>
                            </div>
                        </td>
                    </tr>
                `;
            });
        }

        function openEditQuizModal(idPregunta) {
            const q = quizQuestions.find(item => item.id == idPregunta);
            if (!q) return;

            const opciones = q.options || q.respuestas || [];

            document.getElementById('edit-q-id').value = q.id;
            document.getElementById('edit-q-text').value = q.question || q.enunciado;
            document.getElementById('edit-q-opt-a').value = opciones[0] ? (opciones[0].text || opciones[0].texto_respuesta) : '';
            document.getElementById('edit-q-opt-b').value = opciones[1] ? (opciones[1].text || opciones[1].texto_respuesta) : '';
            document.getElementById('edit-q-opt-c').value = opciones[2] ? (opciones[2].text || opciones[2].texto_respuesta) : '';

            const correctOpt = opciones.find(o => o.correct || o.es_correcta == 1);
            if (correctOpt) {
                document.getElementById('edit-q-correct-letter').value = correctOpt.letter || 'A';
            }

            document.getElementById('modal-edit-quiz').classList.add('active');
        }

        function closeEditQuizModal() {
            document.getElementById('modal-edit-quiz').classList.remove('active');
        }

        document.getElementById('form-edit-quiz-question').addEventListener('submit', async function(e) {
            e.preventDefault();
            const idPregunta = document.getElementById('edit-q-id').value;
            const qText = document.getElementById('edit-q-text').value.trim();
            const optA = document.getElementById('edit-q-opt-a').value.trim();
            const optB = document.getElementById('edit-q-opt-b').value.trim();
            const optC = document.getElementById('edit-q-opt-c').value.trim();
            const correctLetter = document.getElementById('edit-q-correct-letter').value;

            if (qText === '' || optA === '' || optB === '' || optC === '') {
                alert('⚠️ No puedes guardar preguntas u opciones vacías.');
                return;
            }

            const payload = {
                id_pregunta: idPregunta,
                enunciado: qText,
                opciones: [
                    { texto: optA, correcta: correctLetter === 'A' },
                    { texto: optB, correcta: correctLetter === 'B' },
                    { texto: optC, correcta: correctLetter === 'C' }
                ]
            };

            const res = await apiPost(API_QUIZ + '?action=update_pregunta', payload);
            if (res.success) {
                alert('¡Pregunta actualizada correctamente!');
                closeEditQuizModal();
                cargarPreguntasQuizBD();
            } else {
                alert(res.message || 'Error al actualizar la pregunta.');
            }
        });

        document.getElementById('form-add-quiz-question').addEventListener('submit', async function(e) {
            e.preventDefault();

            const qText = document.getElementById('q-text').value.trim();
            const optA = document.getElementById('q-opt-a').value.trim();
            const optB = document.getElementById('q-opt-b').value.trim();
            const optC = document.getElementById('q-opt-c').value.trim();
            const correctLetter = document.getElementById('q-correct-letter').value;

            if (qText === '' || optA === '' || optB === '' || optC === '') {
                alert('⚠️ Por favor ingresa textos válidos antes de guardar.');
                return;
            }

            const payload = {
                enunciado: qText,
                opciones: [
                    { texto: optA, correcta: correctLetter === 'A' },
                    { texto: optB, correcta: correctLetter === 'B' },
                    { texto: optC, correcta: correctLetter === 'C' }
                ]
            };

            const res = await apiPost(API_QUIZ + '?action=add_pregunta', payload);

            if (res.success) {
                alert('¡Pregunta guardada correctamente!');
                this.reset();
                await cargarPreguntasQuizBD();
            } else {
                alert(res.message || 'Error al guardar la pregunta.');
            }
        });

        async function eliminarPreguntaQuiz(idPregunta) {
            if (!idPregunta) return;
            if (confirm('¿Deseas eliminar esta pregunta permanentemente?')) {
                const res = await apiPost(API_QUIZ + '?action=delete_pregunta', { id_pregunta: idPregunta });
                if (res.success) {
                    alert('¡Pregunta eliminada!');
                    await cargarPreguntasQuizBD();
                } else {
                    alert('No se pudo eliminar la pregunta.');
                }
            }
        }

        function filterRegion(region) {
            document.querySelectorAll('.btn-region').forEach(btn => btn.classList.remove('active'));
            if (event && event.target) event.target.classList.add('active');
            renderizarPlantas(region);
        }

        function showAuth(mode) {
            document.getElementById('form-login').classList.toggle('hidden', mode !== 'login');
            document.getElementById('form-register').classList.toggle('hidden', mode !== 'register');
            document.getElementById('tab-login').classList.toggle('active', mode === 'login');
            document.getElementById('tab-register').classList.toggle('active', mode !== 'login');
        }

        document.getElementById('form-login').addEventListener('submit', async (e) => {
            e.preventDefault();
            const email = document.getElementById('login-email').value;
            const pass = document.getElementById('login-password').value;
            const msg = document.getElementById('login-msg');

            msg.textContent = 'Verificando...';
            const res = await apiPost(API_REGISTRO + '?action=login', { correo: email, contrasena: pass });

            if (res.success && res.user) {
                currentUser = res.user;

                const localSaved = sessionStorage.getItem('user_stats_' + currentUser.id_usuario);
                if (localSaved) {
                    currentUser.stats = JSON.parse(localSaved);
                }

                // Guardar sesión para que no se pierda al refrescar
                sessionStorage.setItem('usuario_logueado', JSON.stringify(currentUser));

                enterApp();
            } else {
                msg.textContent = res.message || 'Error de credenciales';
                msg.className = 'form-msg error';
            }
        });

        document.getElementById('form-register').addEventListener('submit', async (e) => {
            e.preventDefault();
            const name = document.getElementById('reg-name').value;
            const email = document.getElementById('reg-email').value;
            const pass = document.getElementById('reg-pass').value;
            const msg = document.getElementById('reg-msg');

            const res = await apiPost(API_REGISTRO + '?action=register', { nombre: name, correo: email, contrasena: pass });

            if (res.success) {
                showAuth('login');
                document.getElementById('login-email').value = email;
                document.getElementById('login-msg').textContent = '¡Registrado con éxito! Inicia sesión.';
                document.getElementById('login-msg').className = 'form-msg ok';
            } else {
                msg.textContent = res.message || 'No se pudo crear la cuenta';
                msg.className = 'form-msg error';
            }
        });

        async function enterApp() {
            const authView = document.getElementById('auth-view');
            const appView = document.getElementById('app-view');

            authView.style.display = 'none';
            authView.classList.add('hidden');
            
            appView.style.display = 'flex';
            appView.classList.remove('hidden');

            const name = currentUser.nombre;
            const rol = currentUser.rol ? currentUser.rol.toLowerCase() : 'estudiante';

            document.getElementById('user-name-display').innerText = name.split(' ')[0];
            document.getElementById('user-avatar').innerText = name.charAt(0).toUpperCase();
            document.getElementById('user-avatar-big').innerText = name.charAt(0).toUpperCase();
            document.getElementById('user-display-name').innerText = name;
            document.getElementById('user-display-email').innerText = currentUser.correo;
            document.getElementById('user-display-rol').innerText = rol;

            document.querySelectorAll('.role-only').forEach(el => el.classList.add('hidden'));

            if (rol === 'docente' || rol === 'admin') {
                document.getElementById('nav-alumnos').classList.remove('hidden');
            }
            if (rol === 'admin') {
                document.getElementById('nav-admin').classList.remove('hidden');
            }

            await cargarProgresoUsuario();
            cargarPlantasBD();
            cargarPreguntasQuizBD();
            switchTab('catalogo');
        }

        function logout() {
            currentUser = null;
            closeUserDropdown();

            // Borrar sesión guardada
            sessionStorage.removeItem('usuario_logueado');

            document.getElementById('login-email').value = '';
            document.getElementById('login-password').value = '';

            const authView = document.getElementById('auth-view');
            const appView = document.getElementById('app-view');

            appView.style.display = 'none';
            appView.classList.add('hidden');

            authView.style.display = 'grid';
            authView.classList.remove('hidden');

            showAuth('login');
        }

        async function cargarProgresoAlumnos() {
            const res = await fetch(API_REGISTRO + '?action=get_alumnos_progreso');
            const data = await res.json();
            const container = document.getElementById('alumnos-cards-container');
            container.innerHTML = '';

            if (data.success && data.alumnos.length > 0) {
                listaAlumnosGlobal = data.alumnos;
                data.alumnos.forEach((a, index) => {
                    const inicial = a.nombre.charAt(0).toUpperCase();
                    container.innerHTML += `
                        <div class="alumno-card" onclick="openAlumnoModal(${index})">
                            <div>
                                <span class="alumno-badge">🌱 Estudiante Activo</span>
                                <div class="alumno-header">
                                    <div class="alumno-avatar">${inicial}</div>
                                    <div class="alumno-info-text">
                                        <h3 class="alumno-nombre">${a.nombre}</h3>
                                        <span class="alumno-email">${a.correo}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="alumno-stats">
                                <div class="alumno-stat-item">
                                    <span class="stat-val">${a.quizzes}</span>
                                    <span class="stat-lbl">Quizzes</span>
                                </div>
                                <div class="alumno-stat-item">
                                    <span class="stat-val score-val">${a.puntos} pts</span>
                                    <span class="stat-lbl">Acumulados</span>
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
        }

        function openAlumnoModal(index) {
            const alumno = listaAlumnosGlobal[index];
            if (!alumno) return;

            document.getElementById('modal-alumno-nombre').innerText = alumno.nombre;
            document.getElementById('modal-alumno-email').innerText = alumno.correo;
            document.getElementById('modal-alumno-avatar').innerText = alumno.nombre.charAt(0).toUpperCase();
            document.getElementById('modal-alumno-total-quizzes').innerText = alumno.quizzes;
            document.getElementById('modal-alumno-total-pts').innerText = alumno.puntos + ' pts';

            const listContainer = document.getElementById('modal-alumno-quiz-list');
            listContainer.innerHTML = '';

            if (alumno.quizzes > 0) {
                const numQuizzes = alumno.quizzes;
                const totalPuntos = alumno.puntos;
                const basePuntosPorQuiz = Math.floor(totalPuntos / numQuizzes);

                for (let i = 1; i <= numQuizzes; i++) {
                    const pts = (i === numQuizzes) ? (totalPuntos - (basePuntosPorQuiz * (numQuizzes - 1))) : basePuntosPorQuiz;
                    listContainer.innerHTML += `
                        <div class="quiz-detail-item" style="display:flex; justify-content:space-between; align-items:center; padding:12px; background:#fff0f5; border-radius:10px; margin-bottom:8px; border:1px solid #f8b4d0;">
                            <span class="quiz-detail-title" style="font-weight:700; color:#8c2347;">Quiz #${i} Completado</span>
                            <span class="quiz-detail-pts" style="background:#1b4332; color:#fff; padding:4px 12px; border-radius:20px; font-weight:800; font-size:0.85rem;">+${pts} pts</span>
                        </div>
                    `;
                }
            } else {
                listContainer.innerHTML = `<p style="font-size:0.88rem; color:var(--text-muted); text-align:center;">El estudiante aún no ha completado ningún quiz.</p>`;
            }

            document.getElementById('modal-alumno-detail').classList.add('active');
        }

        function closeAlumnoModal() {
            document.getElementById('modal-alumno-detail').classList.remove('active');
        }

        async function cargarTodosUsuariosAdmin() {
            const res = await fetch(API_REGISTRO + '?action=get_todos_usuarios');
            const data = await res.json();
            const tbody = document.getElementById('tabla-admin-usuarios');
            tbody.innerHTML = '';

            if (data.success && data.usuarios.length > 0) {
                data.usuarios.forEach(u => {
                    tbody.innerHTML += `
                        <tr style="border-bottom:1px solid #eee;">
                            <td style="padding:12px;"><strong>#${u.id_usuario}</strong></td>
                            <td style="padding:12px;">${u.nombre}</td>
                            <td style="padding:12px;">${u.correo}</td>
                            <td style="padding:12px;"><span class="role-badge role-${u.rol.toLowerCase()}">${u.rol}</span></td>
                            <td style="padding:12px;">
                                <select class="select-rol" onchange="cambiarRolUsuario(${u.id_usuario}, this.value)">
                                    <option value="estudiante" ${u.rol.toLowerCase() === 'estudiante' ? 'selected' : ''}>Estudiante</option>
                                    <option value="docente" ${u.rol.toLowerCase() === 'docente' ? 'selected' : ''}>Docente</option>
                                    <option value="admin" ${u.rol.toLowerCase() === 'admin' ? 'selected' : ''}>Admin</option>
                                </select>
                            </td>
                            <td style="padding:12px;">
                                <button class="btn-delete-admin" onclick="eliminarUsuario(${u.id_usuario})">🗑️ Eliminar</button>
                            </td>
                        </tr>
                    `;
                });
            }
        }

        async function cambiarRolUsuario(idUsuario, nuevoRol) {
            const res = await apiPost(API_REGISTRO + '?action=update_rol', { id_usuario: idUsuario, rol: nuevoRol });
            if (res.success) {
                alert('¡Rol actualizado!');
                cargarTodosUsuariosAdmin();
            }
        }

        async function eliminarUsuario(idUsuario) {
            if (!confirm('¿Seguro que deseas eliminar este usuario del sistema?')) return;
            const res = await apiPost(API_REGISTRO + '?action=delete_usuario', { id_usuario: idUsuario });
            if (res.success) {
                alert('¡Usuario eliminado correctamente!');
                cargarTodosUsuariosAdmin();
            } else {
                alert(res.message || 'No se pudo eliminar el usuario.');
            }
        }

        function previewAddImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('p-preview-img').src = e.target.result;
                    document.getElementById('p-imagen-url').value = 'img/' + file.name;
                };
                reader.readAsDataURL(file);
                document.getElementById('p-file-name').innerText = file.name;
            }
        }

        function previewEditImage(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('edit-p-preview-img').src = e.target.result;
                    document.getElementById('edit-p-imagen').value = 'img/' + file.name;
                };
                reader.readAsDataURL(file);
                document.getElementById('edit-p-file-name').innerText = file.name;
            }
        }

        document.getElementById('form-add-planta').addEventListener('submit', async function(e) {
            e.preventDefault();
            const cuidadosVal = document.getElementById('p-cuidados').value.trim();

            if (esTextoIlegibleOAzar(cuidadosVal)) {
                alert('⚠️ La descripción contiene secuencias inválidas. Ingresa un texto entendible.');
                document.getElementById('warn-p-cuidados').classList.add('active');
                return;
            }

            const body = {
                nombre_comun: document.getElementById('p-nombre-comun').value.trim(),
                nombre_cientifico: document.getElementById('p-nombre-cientifico').value.trim(),
                id_region: document.getElementById('p-region').value,
                riego: document.getElementById('p-riego').value.trim(),
                luz: document.getElementById('p-luz').value.trim(),
                suelo: document.getElementById('p-suelo').value.trim(),
                cuidados: cuidadosVal,
                imagen_url: document.getElementById('p-imagen-url').value || ''
            };

            const res = await apiPost(API_PLANTAS + '?action=add_planta', body);

            if (res.success) {
                alert('¡Planta agregada!');
                this.reset();
                quitarImagenAdd();
                document.getElementById('warn-p-cuidados').classList.remove('active');
                await cargarPlantasBD();
            } else {
                alert('Error al guardar: ' + (res.message || 'No se pudo guardar la planta.'));
            }
        });

        function openEditModal(idPlanta) {
            const p = listaPlantas.find(item => item.id_planta == idPlanta);
            if (!p) return;

            document.getElementById('edit-p-id').value = p.id_planta;
            document.getElementById('edit-p-nombre-comun').value = p.nombre_comun;
            document.getElementById('edit-p-nombre-cientifico').value = p.nombre_cientifico;
            document.getElementById('edit-p-region').value = p.id_region || "1";
            document.getElementById('edit-p-riego').value = p.riego;
            document.getElementById('edit-p-luz').value = p.luz;
            document.getElementById('edit-p-suelo').value = p.suelo;
            document.getElementById('edit-p-cuidados').value = p.cuidados;
            
            const currentImg = (p.imagen_url && p.imagen_url.trim() !== '') ? p.imagen_url : DEFAULT_SVG_PREVIEW;
            document.getElementById('edit-p-imagen').value = p.imagen_url || '';
            document.getElementById('edit-p-preview-img').src = currentImg;

            const filename = (p.imagen_url) ? p.imagen_url.split('/').pop() : 'Seleccionar archivo...';
            document.getElementById('edit-p-file-name').innerText = filename || 'Seleccionar archivo...';

            document.getElementById('modal-edit-plant').classList.add('active');
        }

        function closeEditModal() {
            document.getElementById('modal-edit-plant').classList.remove('active');
        }

        document.getElementById('form-edit-planta').addEventListener('submit', async function(e) {
            e.preventDefault();
            const body = {
                id_planta: document.getElementById('edit-p-id').value,
                nombre_comun: document.getElementById('edit-p-nombre-comun').value.trim(),
                nombre_cientifico: document.getElementById('edit-p-nombre-cientifico').value.trim(),
                id_region: document.getElementById('edit-p-region').value,
                riego: document.getElementById('edit-p-riego').value.trim(),
                luz: document.getElementById('edit-p-luz').value.trim(),
                suelo: document.getElementById('edit-p-suelo').value.trim(),
                cuidados: document.getElementById('edit-p-cuidados').value.trim(),
                imagen_url: document.getElementById('edit-p-imagen').value
            };

            const res = await apiPost(API_PLANTAS + '?action=update_planta', body);
            if (res.success) {
                alert('¡Planta actualizada!');
                closeEditModal();
                await cargarPlantasBD();
            } else {
                alert(res.message || 'Error al guardar los cambios.');
            }
        });

        async function eliminarPlanta(idPlanta) {
            if (!confirm('¿Seguro que deseas eliminar esta planta del catálogo?')) return;
            const res = await apiPost(API_PLANTAS + '?action=delete_planta', { id_planta: idPlanta });
            if (res.success) {
                alert('¡Planta eliminada correctamente!');
                await cargarPlantasBD();
            } else {
                alert(res.message || 'Error al eliminar la planta.');
            }
        }

        function openModal(idPlanta) {
            const p = listaPlantas.find(item => item.id_planta == idPlanta);
            if (!p) return;

            document.getElementById('modal-title').innerText = p.nombre_comun;
            document.getElementById('modal-latin').innerText = p.nombre_cientifico;
            document.getElementById('modal-riego').innerText = p.riego || 'Moderado';
            document.getElementById('modal-luz').innerText = p.luz || 'Sol directo';
            document.getElementById('modal-suelo').innerText = p.suelo || 'Suelo bien drenado';
            document.getElementById('modal-cuidados').innerText = p.cuidados || 'Sin observaciones.';
            
            const imgModal = document.getElementById('modal-img');
            const rutaImagen = (p.imagen_url && p.imagen_url.trim() !== '') ? p.imagen_url : DEFAULT_SVG_PREVIEW;

            imgModal.onerror = function() {
                this.onerror = null;
                this.src = DEFAULT_SVG_PREVIEW;
            };
            imgModal.src = rutaImagen;

            document.getElementById('modal-plant').classList.add('active');
        }

        function closeModal() {
            document.getElementById('modal-plant').classList.remove('active');
        }

        async function switchTab(tabName) {
            document.getElementById('app-view').setAttribute('data-tab', tabName);
            document.querySelectorAll('.section').forEach(sec => sec.classList.remove('active'));
            const heroEl = document.querySelector('.hero');

            if (tabName === 'catalogo') {
                document.getElementById('sec-catalogo').classList.add('active');
                if (heroEl) heroEl.style.display = 'flex';
                await cargarPlantasBD();
            } else {
                if (heroEl) heroEl.style.display = 'none';
                if (tabName === 'quiz') {
                    document.getElementById('sec-quiz').classList.add('active');
                    await cargarPreguntasQuizBD();
                } else if (tabName === 'progreso') {
                    document.getElementById('sec-progreso').classList.add('active');
                    await cargarProgresoUsuario();
                } else if (tabName === 'alumnos') {
                    document.getElementById('sec-alumnos').classList.add('active');
                    cargarProgresoAlumnos();
                } else if (tabName === 'admin') {
                    document.getElementById('sec-admin').classList.add('active');
                    cargarTodosUsuariosAdmin();
                    renderizarTablaQuizAdmin();
                    await cargarPlantasBD();
                }
            }

            document.querySelectorAll('.main-nav .nav-link').forEach(btn => btn.classList.remove('active'));
            const targetNavBtn = document.querySelector(`.main-nav .nav-link[onclick*="'${tabName}'"]`);
            if (targetNavBtn) targetNavBtn.classList.add('active');
        }

        function toggleUserDropdown(e) {
            e.stopPropagation();
            document.getElementById('user-dropdown').classList.toggle('show');
        }

        function closeUserDropdown() {
            const dropdown = document.getElementById('user-dropdown');
            if (dropdown) dropdown.classList.remove('show');
        }

        window.addEventListener('click', closeUserDropdown);
    </script>
</body>
</html>
