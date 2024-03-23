export default {
    containers: [
        { 
            id: 'request',
            uid: null,
            component: {
                name: "Request",
            },
            name: "Request",
            icon: "bi bi-send-plus-fill",
            type: "container",
            widgets: [],
            settings: {
                title: "",
            },
            openToggle: true,
        },
    ],
    widgets: [
        { 
            id: "text",
            uid: null,
            component: {
                name: "Text",
            },
            name: "Text",
            icon: "bi bi-textarea-t",
            type: "widget",
            settings: {
                role: "user",
                content: "",
                new_lines_before: 0,
                new_lines_after: 0,
                title: "",
            },
            openToggle: true,
        },
        { 
            id: "compose",
            uid: null,
            component: {
                name: "Compose",
            },
            name: "Compose",
            icon: "bi bi-collection-fill",
            type: "widget",
            widgets: [],
            settings: {
                role: "user",
                new_lines_before: 0,
                new_lines_after: 0,
                title: "",
            },
            openToggle: true,
        },
        { 
            id: "input",
            uid: null,
            component: {
                name: "Input",
            },
            name: "Input",
            icon: "bi bi-box-arrow-in-down",
            type: "widget",
            settings: {
                role: "user",
                input: "",
                new_lines_before: 0,
                new_lines_after: 0,
                title: "",                
            },
            openToggle: true,
        },
        { 
            id: "relation",
            uid: null,
            component: {
                name: "Relation",
            },
            name: "Relation",
            icon: "bi bi-circle-square",
            type: "widget",
            settings: {
                role: "user",
                relation: "",
                new_lines_before: 0,
                new_lines_after: 0,
                title: "",
            },
            openToggle: true,
        },
    ],
}