# YieldBreakableCaller - Workflow

```mermaid
flowchart TD
    A[Start: Create BreakableCaller instance] --> B[Define generator task callback]
    B --> C[Define shouldNext callback (break condition)]
    C --> D[Call invoke(task, shouldNext)]
    D --> E{Generator valid?}
    E -- Yes --> F[generator->next()]
    F --> G[shouldNext()]
    G -- true --> E
    G -- false --> H[Break: Stop execution]
    E -- No --> I[End]
```

## 说明

- 任务以 Generator 形式分步执行
- 每步执行后通过 shouldNext 判断是否继续
- shouldNext 返回 false 时立即中断
- 适用于可控流程、分步处理等场景
