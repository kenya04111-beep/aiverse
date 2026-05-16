const express = require('express');
const fs = require('fs');
const path = require('path');
const app = express();
const PORT = 3000;

app.use(express.json());
app.use(express.static('public'));

const DATA_FILE = path.join(__dirname, 'articles.json');

// 記事の一覧を取得
app.get('/api/articles', (req, res) => {
    if (!fs.existsSync(DATA_FILE)) return res.json([]);
    const data = fs.readFileSync(DATA_FILE);
    res.json(JSON.parse(data));
});

// 記事を投稿（ここに保存されるのでクッキーを消しても残ります）
app.post('/api/articles', (req, res) => {
    const newArticle = { id: Date.now(), text: req.body.text };
    let articles = [];
    if (fs.existsSync(DATA_FILE)) {
        articles = JSON.parse(fs.readFileSync(DATA_FILE));
    }
    articles.push(newArticle);
    fs.writeFileSync(DATA_FILE, JSON.stringify(articles, null, 2));
    res.json({ success: true });
});

app.listen(PORT, () => console.log(`Server running on http://localhost:${PORT}`));
