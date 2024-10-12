export default function useUserPolicy() {

    const viewSuperAdminTopics = (userRoles) => {
        return userRoles.some(role => role.name == 'super_administrador');
    };

    const viewAdminTopics = (userRoles) => {
        return userRoles.some(role => role.name == 'administrador' || role.name == 'super_administrador');
    };

    const viewVendorTopics = (userRoles) => {
        return userRoles.some(role => role.name == 'vendedor' || role.name == 'administrador' || role.name == 'super_administrador');
    };

    const viewMemberTopics = (userRoles) => {
        return userRoles.some(role => role.name == 'miembro' || role.name == 'vendedor' || role.name == 'administrador' || role.name == 'super_administrador');
    };

    return {
        viewSuperAdminTopics,
        viewAdminTopics,
        viewVendorTopics,
        viewMemberTopics
    };
}
