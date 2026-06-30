# OpenDataLoader PDF

GitHub: https://github.com/opendataloader-project/opendataloader-pdf

## What it is

OpenDataLoader PDF is a PDF ingestion/conversion tool.

It can convert PDFs into structured formats such as:

- Markdown
- JSON
- HTML

This is useful because Bringora may need to read user-uploaded PDFs and turn them into clean text, tasks, summaries, or searchable knowledge.

## Why it matters for Bringora

Bringora may later support:

- PDF upload
- document Q&A
- school handout summary
- recipe PDF extraction
- government form explanation
- business document checklist
- personal knowledge base
- task extraction from documents

Instead of sending the whole PDF to AI every time, we can convert the PDF once, clean it, chunk it, store it, and only send relevant parts to the AI.

## Token-saving logic

Bad flow:

```text
User uploads PDF
→ AI reads entire PDF every time
→ high token cost
