const fs = require('fs');
const http = require('http');

http.get('http://127.0.0.1:8000/peta/20?school=28', (res) => {
    let data = '';
    res.on('data', chunk => data += chunk);
    res.on('end', () => {
        // Find x-data on body
        const match = data.match(/<body[^>]*x-data="([^"]*)"/s);
        if (!match) {
            console.log("Could not match x-data with regex /<body[^>]*x-data=\"([^\"]*)\"/");
            const bodyStart = data.indexOf('<body');
            const xDataStart = data.indexOf('x-data="', bodyStart);
            console.log("xData starts at:", xDataStart);
            // Let's see what character terminated the double quote!
            const quoteAfter = data.indexOf('"', xDataStart + 8);
            console.log("Next quote is at:", quoteAfter);
            console.log("Snippet around next quote:", JSON.stringify(data.substring(quoteAfter - 20, quoteAfter + 20)));
            return;
        }
        console.log("Matched x-data length:", match[1].length);
        try {
            // Unescape HTML entities
            const unescaped = match[1]
                .replace(/&quot;/g, '"')
                .replace(/&#039;/g, "'")
                .replace(/&lt;/g, '<')
                .replace(/&gt;/g, '>')
                .replace(/&amp;/g, '&');
            new Function(`return (${unescaped})`)();
            console.log("Successfully evaluated x-data JS!");
        } catch (err) {
            console.error("Evaluation error:", err.message);
        }
    });
});
