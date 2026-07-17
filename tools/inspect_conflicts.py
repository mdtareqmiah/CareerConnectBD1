from pathlib import Path
path = Path('resources/views/jobs/show.blade.php')
text = path.read_text('utf-8')
lines = text.splitlines()
for i, line in enumerate(lines, 1):
    if line.startswith('<<<<<<<') or line.startswith('=======') or line.startswith('>>>>>>>'):
        print(i, repr(line))
