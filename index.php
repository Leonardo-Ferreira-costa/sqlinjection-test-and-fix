<?php
require_once 'config.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Obtém dados do usuário da sessão
$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Financeiro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --accent: #4895ef;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --warning: #f72585;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f5f7fa;
            color: var(--dark);
            line-height: 1.6;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 20px 0;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        
        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .logo {
            font-size: 24px;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .logo i {
            margin-right: 10px;
            font-size: 28px;
        }
        
        .user-info {
            display: flex;
            align-items: center;
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
            object-fit: cover;
            border: 2px solid white;
        }
        
        .logout-btn {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s;
            margin-left: 15px;
        }
        
        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.3);
        }
        
        .welcome-message {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .welcome-message h1 {
            font-size: 2.5rem;
            margin-bottom: 10px;
            color: var(--dark);
        }
        
        .welcome-message p {
            font-size: 1.1rem;
            color: #666;
        }
        
        .cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }
        
        .card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .card-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
        }
        
        .card-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: white;
        }
        
        .dolar-icon {
            background: linear-gradient(45deg, #2ecc71, #27ae60);
        }
        
        .bova-icon {
            background: linear-gradient(45deg, #e74c3c, #c0392b);
        }
        
        .quote-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .quote-change {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
        }
        
        .positive {
            background-color: rgba(46, 204, 113, 0.1);
            color: #27ae60;
        }
        
        .negative {
            background-color: rgba(231, 76, 60, 0.1);
            color: #c0392b;
        }
        
        .quote-time {
            font-size: 0.9rem;
            color: #777;
            margin-top: 15px;
        }
        
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100px;
        }
        
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        .news-container {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-top: 30px;
        }
        
        .news-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 20px;
        }
        
        .news-list {
            list-style: none;
        }
        
        .news-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .news-item:last-child {
            border-bottom: none;
        }
        
        .news-source {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background-color: var(--accent);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: bold;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .news-content {
            flex-grow: 1;
        }
        
        .news-time {
            font-size: 0.8rem;
            color: #999;
            margin-left: 15px;
            white-space: nowrap;
        }
        
        footer {
            text-align: center;
            margin-top: 50px;
            padding: 20px;
            color: #666;
            font-size: 0.9rem;
        }
        
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                text-align: center;
            }
            
            .user-info {
                margin-top: 15px;
            }
            
            .welcome-message h1 {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-content">
            <div class="logo">
                <i class="fas fa-chart-line"></i>
                <span>FinanceDash</span>
            </div>
            <div class="user-info">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['username']) ?>&background=random" alt="User">
                <span><?= htmlspecialchars($user['username']) ?></span>
                <a href="logout.php" class="logout-btn">Sair <i class="fas fa-sign-out-alt"></i></a>
            </div>
        </div>
    </header>
    
    <div class="container">
        <div class="welcome-message">
            <h1>Olá, <?= htmlspecialchars($user['username']) ?>!</h1>
            <p>Acompanhe as principais cotações do mercado</p>
        </div>
        
        <div class="cards-container">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Dólar Comercial</h2>
                    <div class="card-icon dolar-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                </div>
                <div id="dolar-data">
                    <div class="loading">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">BOVA11</h2>
                    <div class="card-icon bova-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                </div>
                <div id="bova-data">
                    <div class="loading">
                        <div class="spinner"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="news-container">
            <h2 class="news-title">Últimas Notícias Financeiras</h2>
            <ul class="news-list" id="news-list">
                <li class="news-item">
                    <div class="loading">
                        <div class="spinner"></div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    
    <footer>
        <p>© <?= date('Y') ?> FinanceDash - Todos os direitos reservados</p>
    </footer>
    
    <script>
        // Função para buscar a cotação do dólar
        async function fetchDolar() {
            try {
                const response = await fetch('https://economia.awesomeapi.com.br/json/last/USD-BRL');
                const data = await response.json();
                const dolar = data.USDBRL;
                
                const changePercent = parseFloat(dolar.pctChange);
                const changeClass = changePercent >= 0 ? 'positive' : 'negative';
                const changeIcon = changePercent >= 0 ? '▲' : '▼';
                
                document.getElementById('dolar-data').innerHTML = `
                    <div class="quote-value">R$ ${parseFloat(dolar.bid).toFixed(2)}</div>
                    <span class="quote-change ${changeClass}">
                        ${changeIcon} ${Math.abs(changePercent)}% (${parseFloat(dolar.ask).toFixed(2)} / ${parseFloat(dolar.bid).toFixed(2)})
                    </span>
                    <div class="quote-time">Atualizado: ${new Date(dolar.create_date).toLocaleString()}</div>
                `;
            } catch (error) {
                document.getElementById('dolar-data').innerHTML = `
                    <div style="color: var(--warning);">Não foi possível carregar a cotação do dólar</div>
                `;
                console.error('Erro ao buscar dólar:', error);
            }
        }
        
        // Função para buscar a cotação do BOVA11
        async function fetchBOVA11() {
            try { 
                const response = await fetch('https://brapi.dev/api/quote/BOVA11?range=1d&interval=1d&fundamental=false&token=eJGEyu8vVHctULdVdHYzQd');
                const data = await response.json();
                const bova = data.results[0];
                
                const changePercent = bova.regularMarketChangePercent;
                const changeClass = changePercent >= 0 ? 'positive' : 'negative';
                const changeIcon = changePercent >= 0 ? '▲' : '▼';
                
                document.getElementById('bova-data').innerHTML = `
                    <div class="quote-value">R$ ${bova.regularMarketPrice.toFixed(2)}</div>
                    <span class="quote-change ${changeClass}">
                        ${changeIcon} ${Math.abs(changePercent).toFixed(2)}%
                    </span>
                    <div class="quote-time">Atualizado: ${new Date(bova.regularMarketTime * 1000).toLocaleString()}</div>
                `;
            } catch (error) {
                document.getElementById('bova-data').innerHTML = `
                    <div style="color: var(--warning);">Não foi possível carregar a cotação do BOVA11</div>
                `;
                console.error('Erro ao buscar BOVA11:', error);
            }
        }
        
        // Função para buscar notícias financeiras
        async function fetchNews() {
            try {
                const response = await fetch('https://newsapi.org/v2/top-headlines?country=us&category=business&apiKey=b9961102615e4514b7f1ff574f4ce412');
                const data = await response.json();
                
                const newsList = document.getElementById('news-list');
                newsList.innerHTML = '';
                
                data.articles.slice(0, 5).forEach(article => {
                    const newsItem = document.createElement('li');
                    newsItem.className = 'news-item';
                    
                    const source = article.source.name.substring(0, 2).toUpperCase();
                    const time = new Date(article.publishedAt).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                    
                    newsItem.innerHTML = `
                        <div class="news-source">${source}</div>
                        <div class="news-content">
                            <strong>${article.title}</strong>
                            <p>${article.description || ''}</p>
                        </div>
                        <div class="news-time">${time}</div>
                    `;
                    
                    newsList.appendChild(newsItem);
                });
            } catch (error) {
                document.getElementById('news-list').innerHTML = `
                    <li class="news-item" style="color: var(--warning);">
                        Não foi possível carregar as notícias
                    </li>
                `;
                console.error('Erro ao buscar notícias:', error);
            }
        }
        
        // Carrega todos os dados quando a página é carregada
        document.addEventListener('DOMContentLoaded', () => {
            fetchDolar();
            fetchBOVA11();
            fetchNews();
            
            // Atualiza os dados a cada 1 minuto
            setInterval(() => {
                fetchDolar();
                fetchBOVA11();
                fetchNews();
            }, 60000);
        });
    </script>
</body>
</html>