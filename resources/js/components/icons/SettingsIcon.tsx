import React from 'react';
import { IconProps } from '@/interfaces/IconProps';
import { classNames } from '@/support/Global';

export function SettingsIcon({ className }: IconProps) {
  return (
    <svg
      className={classNames(className ?? '', '')}
      width="33"
      height="32"
      viewBox="0 0 33 32"
      fill="none"
      xmlns="http://www.w3.org/2000/svg"
    >
      <mask id="path-1-inside-1_818_2787" fill="white">
        <path d="M13.347 3.834C13.3886 3.33405 13.6166 2.86801 13.9858 2.5283C14.355 2.18859 14.8383 2.00002 15.34 2H17.66C18.1617 2.00002 18.645 2.18859 19.0142 2.5283C19.3834 2.86801 19.6114 3.33405 19.653 3.834L19.888 6.659C19.8957 6.75044 19.9284 6.83801 19.9825 6.91211C20.0367 6.9862 20.1102 7.04397 20.1949 7.07908C20.2797 7.11419 20.3725 7.12529 20.4632 7.11117C20.5539 7.09704 20.6389 7.05824 20.709 6.999L22.873 5.168C23.2559 4.8439 23.7466 4.67553 24.2478 4.69627C24.7491 4.71702 25.2242 4.92537 25.579 5.28L27.219 6.92C27.5741 7.27473 27.7828 7.74999 27.8037 8.25145C27.8246 8.75291 27.6563 9.24391 27.332 9.627L25.502 11.79C25.4425 11.8601 25.4034 11.9452 25.3892 12.036C25.3749 12.1268 25.3859 12.2198 25.421 12.3048C25.4562 12.3898 25.5141 12.4634 25.5883 12.5176C25.6626 12.5718 25.7504 12.6044 25.842 12.612L28.666 12.847C29.1659 12.8886 29.632 13.1166 29.9717 13.4858C30.3114 13.855 30.5 14.3383 30.5 14.84V17.16C30.5 17.6617 30.3114 18.145 29.9717 18.5142C29.632 18.8834 29.1659 19.1114 28.666 19.153L25.841 19.388C25.7496 19.3957 25.662 19.4284 25.5879 19.4825C25.5138 19.5367 25.456 19.6102 25.4209 19.6949C25.3858 19.7797 25.3747 19.8725 25.3888 19.9632C25.403 20.0539 25.4418 20.1389 25.501 20.209L27.332 22.373C27.6561 22.7559 27.8245 23.2466 27.8037 23.7478C27.783 24.2491 27.5746 24.7242 27.22 25.079L25.58 26.719C25.2253 27.0741 24.75 27.2828 24.2485 27.3037C23.7471 27.3246 23.2561 27.1563 22.873 26.832L20.709 25.002C20.6389 24.943 20.554 24.9045 20.4634 24.8905C20.3729 24.8765 20.2803 24.8877 20.1957 24.9228C20.1111 24.9579 20.0377 25.0155 19.9836 25.0895C19.9295 25.1634 19.8968 25.2507 19.889 25.342L19.653 28.166C19.6114 28.6659 19.3834 29.132 19.0142 29.4717C18.645 29.8114 18.1617 30 17.66 30H15.34C14.8383 30 14.355 29.8114 13.9858 29.4717C13.6166 29.132 13.3886 28.6659 13.347 28.166L13.112 25.341C13.1044 25.2494 13.0718 25.1616 13.0176 25.0873C12.9634 25.0131 12.8898 24.9552 12.8048 24.92C12.7198 24.8849 12.6268 24.8739 12.536 24.8882C12.4452 24.9024 12.3601 24.9415 12.29 25.001L10.127 26.832C9.7441 27.1561 9.25338 27.3245 8.75216 27.3037C8.25093 27.283 7.77581 27.0746 7.421 26.72L5.781 25.08C5.42594 24.7253 5.21723 24.25 5.1963 23.7485C5.17537 23.2471 5.34374 22.7561 5.668 22.373L7.498 20.209C7.55696 20.1389 7.59552 20.054 7.60949 19.9634C7.62346 19.8729 7.61229 19.7803 7.57721 19.6957C7.54213 19.6111 7.48448 19.5377 7.41055 19.4836C7.33662 19.4295 7.24926 19.3968 7.158 19.389L4.334 19.153C3.83405 19.1114 3.36801 18.8834 3.0283 18.5142C2.68859 18.145 2.50002 17.6617 2.5 17.16V14.84C2.50002 14.3383 2.68859 13.855 3.0283 13.4858C3.36801 13.1166 3.83405 12.8886 4.334 12.847L7.159 12.612C7.25063 12.6044 7.3384 12.5718 7.41266 12.5176C7.48693 12.4634 7.54481 12.3898 7.57995 12.3048C7.61509 12.2198 7.62613 12.1268 7.61185 12.036C7.59757 11.9452 7.55852 11.8601 7.499 11.79L5.668 9.627C5.34361 9.24403 5.17506 8.75311 5.19581 8.25165C5.21656 7.75019 5.42508 7.27486 5.78 6.92L7.42 5.28C7.77473 4.92494 8.24999 4.71623 8.75145 4.6953C9.25291 4.67437 9.74391 4.84274 10.127 5.167L12.29 6.997C12.3601 7.05652 12.4452 7.09557 12.536 7.10985C12.6268 7.12413 12.7198 7.11309 12.8048 7.07795C12.8898 7.04281 12.9634 6.98493 13.0176 6.91066C13.0718 6.8364 13.1044 6.74863 13.112 6.657L13.347 3.833V3.834ZM21.5 16C21.5 14.6739 20.9732 13.4021 20.0355 12.4645C19.0979 11.5268 17.8261 11 16.5 11C15.1739 11 13.9021 11.5268 12.9645 12.4645C12.0268 13.4021 11.5 14.6739 11.5 16C11.5 17.3261 12.0268 18.5979 12.9645 19.5355C13.9021 20.4732 15.1739 21 16.5 21C17.8261 21 19.0979 20.4732 20.0355 19.5355C20.9732 18.5979 21.5 17.3261 21.5 16Z" />
      </mask>
      <path
        d="M13.347 3.834C13.3886 3.33405 13.6166 2.86801 13.9858 2.5283C14.355 2.18859 14.8383 2.00002 15.34 2H17.66C18.1617 2.00002 18.645 2.18859 19.0142 2.5283C19.3834 2.86801 19.6114 3.33405 19.653 3.834L19.888 6.659C19.8957 6.75044 19.9284 6.83801 19.9825 6.91211C20.0367 6.9862 20.1102 7.04397 20.1949 7.07908C20.2797 7.11419 20.3725 7.12529 20.4632 7.11117C20.5539 7.09704 20.6389 7.05824 20.709 6.999L22.873 5.168C23.2559 4.8439 23.7466 4.67553 24.2478 4.69627C24.7491 4.71702 25.2242 4.92537 25.579 5.28L27.219 6.92C27.5741 7.27473 27.7828 7.74999 27.8037 8.25145C27.8246 8.75291 27.6563 9.24391 27.332 9.627L25.502 11.79C25.4425 11.8601 25.4034 11.9452 25.3892 12.036C25.3749 12.1268 25.3859 12.2198 25.421 12.3048C25.4562 12.3898 25.5141 12.4634 25.5883 12.5176C25.6626 12.5718 25.7504 12.6044 25.842 12.612L28.666 12.847C29.1659 12.8886 29.632 13.1166 29.9717 13.4858C30.3114 13.855 30.5 14.3383 30.5 14.84V17.16C30.5 17.6617 30.3114 18.145 29.9717 18.5142C29.632 18.8834 29.1659 19.1114 28.666 19.153L25.841 19.388C25.7496 19.3957 25.662 19.4284 25.5879 19.4825C25.5138 19.5367 25.456 19.6102 25.4209 19.6949C25.3858 19.7797 25.3747 19.8725 25.3888 19.9632C25.403 20.0539 25.4418 20.1389 25.501 20.209L27.332 22.373C27.6561 22.7559 27.8245 23.2466 27.8037 23.7478C27.783 24.2491 27.5746 24.7242 27.22 25.079L25.58 26.719C25.2253 27.0741 24.75 27.2828 24.2485 27.3037C23.7471 27.3246 23.2561 27.1563 22.873 26.832L20.709 25.002C20.6389 24.943 20.554 24.9045 20.4634 24.8905C20.3729 24.8765 20.2803 24.8877 20.1957 24.9228C20.1111 24.9579 20.0377 25.0155 19.9836 25.0895C19.9295 25.1634 19.8968 25.2507 19.889 25.342L19.653 28.166C19.6114 28.6659 19.3834 29.132 19.0142 29.4717C18.645 29.8114 18.1617 30 17.66 30H15.34C14.8383 30 14.355 29.8114 13.9858 29.4717C13.6166 29.132 13.3886 28.6659 13.347 28.166L13.112 25.341C13.1044 25.2494 13.0718 25.1616 13.0176 25.0873C12.9634 25.0131 12.8898 24.9552 12.8048 24.92C12.7198 24.8849 12.6268 24.8739 12.536 24.8882C12.4452 24.9024 12.3601 24.9415 12.29 25.001L10.127 26.832C9.7441 27.1561 9.25338 27.3245 8.75216 27.3037C8.25093 27.283 7.77581 27.0746 7.421 26.72L5.781 25.08C5.42594 24.7253 5.21723 24.25 5.1963 23.7485C5.17537 23.2471 5.34374 22.7561 5.668 22.373L7.498 20.209C7.55696 20.1389 7.59552 20.054 7.60949 19.9634C7.62346 19.8729 7.61229 19.7803 7.57721 19.6957C7.54213 19.6111 7.48448 19.5377 7.41055 19.4836C7.33662 19.4295 7.24926 19.3968 7.158 19.389L4.334 19.153C3.83405 19.1114 3.36801 18.8834 3.0283 18.5142C2.68859 18.145 2.50002 17.6617 2.5 17.16V14.84C2.50002 14.3383 2.68859 13.855 3.0283 13.4858C3.36801 13.1166 3.83405 12.8886 4.334 12.847L7.159 12.612C7.25063 12.6044 7.3384 12.5718 7.41266 12.5176C7.48693 12.4634 7.54481 12.3898 7.57995 12.3048C7.61509 12.2198 7.62613 12.1268 7.61185 12.036C7.59757 11.9452 7.55852 11.8601 7.499 11.79L5.668 9.627C5.34361 9.24403 5.17506 8.75311 5.19581 8.25165C5.21656 7.75019 5.42508 7.27486 5.78 6.92L7.42 5.28C7.77473 4.92494 8.24999 4.71623 8.75145 4.6953C9.25291 4.67437 9.74391 4.84274 10.127 5.167L12.29 6.997C12.3601 7.05652 12.4452 7.09557 12.536 7.10985C12.6268 7.12413 12.7198 7.11309 12.8048 7.07795C12.8898 7.04281 12.9634 6.98493 13.0176 6.91066C13.0718 6.8364 13.1044 6.74863 13.112 6.657L13.347 3.833V3.834ZM21.5 16C21.5 14.6739 20.9732 13.4021 20.0355 12.4645C19.0979 11.5268 17.8261 11 16.5 11C15.1739 11 13.9021 11.5268 12.9645 12.4645C12.0268 13.4021 11.5 14.6739 11.5 16C11.5 17.3261 12.0268 18.5979 12.9645 19.5355C13.9021 20.4732 15.1739 21 16.5 21C17.8261 21 19.0979 20.4732 20.0355 19.5355C20.9732 18.5979 21.5 17.3261 21.5 16Z"
        fill="#64748B"
      />
      <path
        d="M13.347 3.834H12.347L14.3435 3.917L13.347 3.834ZM15.34 2V1H15.34L15.34 2ZM17.66 2L17.66 1H17.66V2ZM19.653 3.834L20.6496 3.7511L20.6495 3.751L19.653 3.834ZM19.888 6.659L18.8914 6.7419L18.8915 6.74275L19.888 6.659ZM20.709 6.999L21.3545 7.76275L21.3549 7.7624L20.709 6.999ZM22.873 5.168L23.5189 5.9314L23.5191 5.93128L22.873 5.168ZM25.579 5.28L26.2861 4.57289L26.2859 4.57272L25.579 5.28ZM27.219 6.92L26.5119 7.62711L26.5122 7.62743L27.219 6.92ZM27.332 9.627L26.5687 8.98093L26.5686 8.98111L27.332 9.627ZM25.502 11.79L26.2642 12.4374L26.2654 12.4359L25.502 11.79ZM25.842 12.612L25.9249 11.6154L25.9242 11.6154L25.842 12.612ZM28.666 12.847L28.749 11.8505L28.7489 11.8504L28.666 12.847ZM30.5 14.84H31.5V14.84L30.5 14.84ZM30.5 17.16L31.5 17.16V17.16H30.5ZM28.666 19.153L28.7489 20.1496L28.749 20.1495L28.666 19.153ZM25.841 19.388L25.7581 18.3914L25.7572 18.3915L25.841 19.388ZM25.501 20.209L24.7372 20.8545L24.7376 20.8549L25.501 20.209ZM27.332 22.373L26.5686 23.0189L26.5687 23.0191L27.332 22.373ZM27.22 25.079L27.9271 25.7861L27.9273 25.7859L27.22 25.079ZM25.58 26.719L24.8729 26.0119L24.8726 26.0122L25.58 26.719ZM22.873 26.832L23.5191 26.0687L23.5187 26.0684L22.873 26.832ZM20.709 25.002L21.3547 24.2384L21.3526 24.2367L20.709 25.002ZM19.889 25.342L18.8926 25.2567L18.8925 25.2587L19.889 25.342ZM19.653 28.166L18.6565 28.0827L18.6565 28.083L19.653 28.166ZM17.66 30V31H17.66L17.66 30ZM15.34 30L15.34 31H15.34V30ZM13.347 28.166L12.3504 28.2489L12.3505 28.249L13.347 28.166ZM13.112 25.341L12.1154 25.4232L12.1154 25.4239L13.112 25.341ZM12.29 25.001L12.9361 25.7643L12.9374 25.7632L12.29 25.001ZM10.127 26.832L10.7731 27.5953L10.7731 27.5953L10.127 26.832ZM7.421 26.72L6.71389 27.4271L6.71407 27.4273L7.421 26.72ZM5.781 25.08L6.48811 24.3729L6.48778 24.3726L5.781 25.08ZM5.668 22.373L6.43128 23.0191L6.43157 23.0187L5.668 22.373ZM7.498 20.209L8.26158 20.8547L8.26332 20.8526L7.498 20.209ZM7.158 19.389L7.24332 18.3926L7.24128 18.3925L7.158 19.389ZM4.334 19.153L4.41728 18.1565L4.417 18.1565L4.334 19.153ZM2.5 17.16H1.5V17.16L2.5 17.16ZM2.5 14.84L1.5 14.84V14.84H2.5ZM4.334 12.847L4.2511 11.8504L4.251 11.8505L4.334 12.847ZM7.159 12.612L7.07683 11.6154L7.0761 11.6154L7.159 12.612ZM7.499 11.79L6.73575 12.4361L6.73683 12.4374L7.499 11.79ZM5.668 9.627L6.43125 8.9809L6.43105 8.98066L5.668 9.627ZM5.78 6.92L6.48705 7.62716L6.48711 7.62711L5.78 6.92ZM7.42 5.28L8.12711 5.98711L8.12743 5.98678L7.42 5.28ZM10.127 5.167L9.48093 5.93028L9.4811 5.93043L10.127 5.167ZM12.29 6.997L12.9374 6.23483L12.9359 6.23357L12.29 6.997ZM13.112 6.657L12.1154 6.57407L12.1154 6.57483L13.112 6.657ZM13.347 3.833H14.347L12.3504 3.75007L13.347 3.833ZM16.5 11V10V11ZM11.5 16H10.5H11.5ZM14.3435 3.917C14.3644 3.66703 14.4784 3.434 14.663 3.26415L13.3087 1.79245C12.7549 2.30201 12.4129 3.00108 12.3505 3.751L14.3435 3.917ZM14.663 3.26415C14.8475 3.0943 15.0892 3.00001 15.34 3L15.34 1C14.5874 1.00004 13.8624 1.28289 13.3087 1.79245L14.663 3.26415ZM15.34 3H17.66V1H15.34V3ZM17.66 3C17.9108 3.00001 18.1525 3.0943 18.337 3.26415L19.6913 1.79245C19.1376 1.28288 18.4126 1.00004 17.66 1L17.66 3ZM18.337 3.26415C18.5216 3.434 18.6356 3.66702 18.6565 3.917L20.6495 3.751C20.5871 3.00108 20.2451 2.30202 19.6913 1.79245L18.337 3.26415ZM18.6564 3.9169L18.8914 6.7419L20.8846 6.5761L20.6496 3.7511L18.6564 3.9169ZM18.8915 6.74275C18.9146 7.01708 19.0127 7.27977 19.1751 7.50206L20.79 6.32215C20.8441 6.39625 20.8768 6.48381 20.8845 6.57525L18.8915 6.74275ZM19.1751 7.50206C19.3375 7.72435 19.558 7.89765 19.8123 8.00299L20.5776 6.15517C20.6623 6.19028 20.7358 6.24805 20.79 6.32215L19.1751 7.50206ZM19.8123 8.00299C20.0667 8.10832 20.3451 8.14162 20.6171 8.09925L20.3093 6.12308C20.4 6.10896 20.4928 6.12006 20.5776 6.15517L19.8123 8.00299ZM20.6171 8.09925C20.8891 8.05688 21.1443 7.94046 21.3545 7.76275L20.0635 6.23525C20.1336 6.17601 20.2186 6.13721 20.3093 6.12308L20.6171 8.09925ZM21.3549 7.7624L23.5189 5.9314L22.2271 4.4046L20.0631 6.2356L21.3549 7.7624ZM23.5191 5.93128C23.7105 5.76923 23.9559 5.68504 24.2065 5.69542L24.2892 3.69713C23.5374 3.66601 22.8013 3.91857 22.2269 4.40472L23.5191 5.93128ZM24.2065 5.69542C24.4571 5.70579 24.6947 5.80996 24.8721 5.98728L26.2859 4.57272C25.7537 4.04077 25.041 3.72825 24.2892 3.69713L24.2065 5.69542ZM24.8719 5.98711L26.5119 7.62711L27.9261 6.21289L26.2861 4.57289L24.8719 5.98711ZM26.5122 7.62743C26.6897 7.8048 26.7941 8.04243 26.8046 8.29316L28.8028 8.20974C28.7714 7.45755 28.4584 6.74466 27.9258 6.21257L26.5122 7.62743ZM26.8046 8.29316C26.815 8.54389 26.7309 8.78939 26.5687 8.98093L28.0953 10.2731C28.5817 9.69844 28.8342 8.96193 28.8028 8.20974L26.8046 8.29316ZM26.5686 8.98111L24.7386 11.1441L26.2654 12.4359L28.0954 10.2729L26.5686 8.98111ZM24.7398 11.1426C24.5613 11.3528 24.4441 11.6082 24.4013 11.8807L26.377 12.1914C26.3627 12.2822 26.3237 12.3673 26.2642 12.4374L24.7398 11.1426ZM24.4013 11.8807C24.3584 12.1531 24.3916 12.4321 24.497 12.687L26.3451 11.9226C26.3803 12.0075 26.3913 12.1005 26.377 12.1914L24.4013 11.8807ZM24.497 12.687C24.6024 12.9419 24.776 13.1628 24.9988 13.3254L26.1778 11.7098C26.2521 11.764 26.31 11.8376 26.3451 11.9226L24.497 12.687ZM24.9988 13.3254C25.2216 13.4879 25.4849 13.586 25.7598 13.6086L25.9242 11.6154C26.0158 11.6229 26.1036 11.6556 26.1778 11.7098L24.9988 13.3254ZM25.7591 13.6086L28.5831 13.8436L28.7489 11.8504L25.9249 11.6154L25.7591 13.6086ZM28.583 13.8435C28.833 13.8644 29.066 13.9784 29.2359 14.163L30.7076 12.8087C30.198 12.2549 29.4989 11.9129 28.749 11.8505L28.583 13.8435ZM29.2359 14.163C29.4057 14.3475 29.5 14.5892 29.5 14.84L31.5 14.84C31.5 14.0874 31.2171 13.3624 30.7076 12.8087L29.2359 14.163ZM29.5 14.84V17.16H31.5V14.84H29.5ZM29.5 17.16C29.5 17.4108 29.4057 17.6525 29.2359 17.837L30.7076 19.1913C31.2171 18.6376 31.5 17.9126 31.5 17.16L29.5 17.16ZM29.2359 17.837C29.066 18.0216 28.833 18.1356 28.583 18.1565L28.749 20.1495C29.4989 20.0871 30.198 19.7451 30.7076 19.1913L29.2359 17.837ZM28.5831 18.1564L25.7581 18.3914L25.9239 20.3846L28.7489 20.1496L28.5831 18.1564ZM25.7572 18.3915C25.4829 18.4146 25.2202 18.5127 24.9979 18.6751L26.1779 20.29C26.1038 20.3441 26.0162 20.3768 25.9247 20.3845L25.7572 18.3915ZM24.9979 18.6751C24.7756 18.8375 24.6023 19.058 24.497 19.3123L26.3448 20.0776C26.3097 20.1623 26.252 20.2358 26.1779 20.29L24.9979 18.6751ZM24.497 19.3123C24.3917 19.5667 24.3584 19.8451 24.4007 20.1171L26.3769 19.8093C26.391 19.9 26.3799 19.9928 26.3448 20.0776L24.497 19.3123ZM24.4007 20.1171C24.4431 20.3891 24.5595 20.6443 24.7372 20.8545L26.2647 19.5635C26.324 19.6336 26.3628 19.7186 26.3769 19.8093L24.4007 20.1171ZM24.7376 20.8549L26.5686 23.0189L28.0954 21.7271L26.2644 19.5631L24.7376 20.8549ZM26.5687 23.0191C26.7308 23.2105 26.815 23.4559 26.8046 23.7065L28.8029 23.7892C28.834 23.0374 28.5814 22.3013 28.0953 21.7269L26.5687 23.0191ZM26.8046 23.7065C26.7942 23.9571 26.69 24.1947 26.5127 24.3721L27.9273 25.7859C28.4592 25.2537 28.7718 24.541 28.8029 23.7892L26.8046 23.7065ZM26.5129 24.3719L24.8729 26.0119L26.2871 27.4261L27.9271 25.7861L26.5129 24.3719ZM24.8726 26.0122C24.6952 26.1897 24.4576 26.2941 24.2068 26.3046L24.2903 28.3028C25.0424 28.2714 25.7553 27.9584 26.2874 27.4258L24.8726 26.0122ZM24.2068 26.3046C23.9561 26.315 23.7106 26.2309 23.5191 26.0687L22.2269 27.5953C22.8016 28.0817 23.5381 28.3342 24.2903 28.3028L24.2068 26.3046ZM23.5187 26.0684L21.3547 24.2384L20.0633 25.7656L22.2273 27.5956L23.5187 26.0684ZM21.3526 24.2367C21.1423 24.0598 20.8875 23.9441 20.6159 23.9022L20.3109 25.8788C20.2204 25.8648 20.1355 25.8263 20.0654 25.7673L21.3526 24.2367ZM20.6159 23.9022C20.3444 23.8603 20.0665 23.8938 19.8127 23.999L20.5787 25.8465C20.4941 25.8816 20.4014 25.8928 20.3109 25.8788L20.6159 23.9022ZM19.8127 23.999C19.5588 24.1043 19.3388 24.2773 19.1765 24.499L20.7907 25.6799C20.7366 25.7538 20.6633 25.8114 20.5787 25.8465L19.8127 23.999ZM19.1765 24.499C19.0143 24.7208 18.9161 24.9829 18.8926 25.2567L20.8854 25.4273C20.8775 25.5186 20.8448 25.6059 20.7907 25.6799L19.1765 24.499ZM18.8925 25.2587L18.6565 28.0827L20.6495 28.2493L20.8855 25.4253L18.8925 25.2587ZM18.6565 28.083C18.6356 28.333 18.5216 28.566 18.337 28.7359L19.6913 30.2076C20.2451 29.698 20.5871 28.9989 20.6495 28.249L18.6565 28.083ZM18.337 28.7359C18.1525 28.9057 17.9108 29 17.66 29L17.66 31C18.4126 31 19.1376 30.7171 19.6913 30.2076L18.337 28.7359ZM17.66 29H15.34V31H17.66V29ZM15.34 29C15.0892 29 14.8475 28.9057 14.663 28.7359L13.3087 30.2076C13.8624 30.7171 14.5874 31 15.34 31L15.34 29ZM14.663 28.7359C14.4784 28.566 14.3644 28.333 14.3435 28.083L12.3505 28.249C12.4129 28.9989 12.7549 29.698 13.3087 30.2076L14.663 28.7359ZM14.3436 28.0831L14.1086 25.2581L12.1154 25.4239L12.3504 28.2489L14.3436 28.0831ZM14.1086 25.2588C14.086 24.9839 13.9879 24.7206 13.8254 24.4978L12.2098 25.6768C12.1556 25.6026 12.1229 25.5148 12.1154 25.4232L14.1086 25.2588ZM13.8254 24.4978C13.6628 24.275 13.4419 24.1014 13.187 23.996L12.4226 25.8441C12.3376 25.809 12.264 25.7511 12.2098 25.6768L13.8254 24.4978ZM13.187 23.996C12.9321 23.8906 12.6531 23.8574 12.3807 23.9003L12.6914 25.876C12.6005 25.8903 12.5075 25.8793 12.4226 25.8441L13.187 23.996ZM12.3807 23.9003C12.1082 23.9431 11.8528 24.0603 11.6426 24.2388L12.9374 25.7632C12.8673 25.8227 12.7822 25.8617 12.6914 25.876L12.3807 23.9003ZM11.6439 24.2377L9.4809 26.0687L10.7731 27.5953L12.9361 25.7643L11.6439 24.2377ZM9.48093 26.0687C9.28948 26.2308 9.04412 26.315 8.79351 26.3046L8.7108 28.3029C9.46264 28.334 10.1987 28.0814 10.7731 27.5953L9.48093 26.0687ZM8.79351 26.3046C8.5429 26.2942 8.30534 26.19 8.12793 26.0127L6.71407 27.4273C7.24628 27.9592 7.95897 28.2718 8.7108 28.3029L8.79351 26.3046ZM8.12811 26.0129L6.48811 24.3729L5.07389 25.7871L6.71389 27.4271L8.12811 26.0129ZM6.48778 24.3726C6.31025 24.1952 6.2059 23.9576 6.19543 23.7068L4.19717 23.7903C4.22857 24.5424 4.54163 25.2553 5.07422 25.7874L6.48778 24.3726ZM6.19543 23.7068C6.18496 23.4561 6.26915 23.2106 6.43128 23.0191L4.90472 21.7269C4.41833 22.3016 4.16577 23.0381 4.19717 23.7903L6.19543 23.7068ZM6.43157 23.0187L8.26157 20.8547L6.73443 19.5633L4.90443 21.7273L6.43157 23.0187ZM8.26332 20.8526C8.44019 20.6423 8.55589 20.3875 8.59779 20.1159L6.62119 19.8109C6.63516 19.7204 6.67372 19.6355 6.73268 19.5654L8.26332 20.8526ZM8.59779 20.1159C8.6397 19.8444 8.6062 19.5665 8.50095 19.3127L6.65347 20.0787C6.61839 19.9941 6.60722 19.9015 6.62119 19.8109L8.59779 20.1159ZM8.50095 19.3127C8.39571 19.0588 8.22276 18.8388 8.00097 18.6765L6.82013 20.2907C6.7462 20.2366 6.68855 20.1633 6.65347 20.0787L8.50095 19.3127ZM8.00097 18.6765C7.77919 18.5143 7.51711 18.4161 7.24332 18.3926L7.07268 20.3854C6.98142 20.3775 6.89406 20.3448 6.82013 20.2907L8.00097 18.6765ZM7.24128 18.3925L4.41728 18.1565L4.25072 20.1495L7.07472 20.3855L7.24128 18.3925ZM4.417 18.1565C4.16702 18.1356 3.934 18.0216 3.76415 17.837L2.29245 19.1913C2.80202 19.7451 3.50108 20.0871 4.251 20.1495L4.417 18.1565ZM3.76415 17.837C3.5943 17.6525 3.50001 17.4108 3.5 17.16L1.5 17.16C1.50004 17.9126 1.78288 18.6376 2.29245 19.1913L3.76415 17.837ZM3.5 17.16V14.84H1.5V17.16H3.5ZM3.5 14.84C3.50001 14.5892 3.5943 14.3475 3.76415 14.163L2.29245 12.8087C1.78289 13.3624 1.50004 14.0874 1.5 14.84L3.5 14.84ZM3.76415 14.163C3.934 13.9784 4.16703 13.8644 4.417 13.8435L4.251 11.8505C3.50108 11.9129 2.80201 12.2549 2.29245 12.8087L3.76415 14.163ZM4.4169 13.8436L7.2419 13.6086L7.0761 11.6154L4.2511 11.8504L4.4169 13.8436ZM7.24117 13.6086C7.51605 13.586 7.77936 13.4879 8.00215 13.3254L6.82317 11.7098C6.89743 11.6556 6.9852 11.6229 7.07683 11.6154L7.24117 13.6086ZM8.00215 13.3254C8.22495 13.1628 8.3986 12.9419 8.50402 12.687L6.65588 11.9226C6.69102 11.8376 6.7489 11.764 6.82317 11.7098L8.00215 13.3254ZM8.50402 12.687C8.60945 12.4321 8.64255 12.1531 8.59971 11.8807L6.62399 12.1914C6.6097 12.1005 6.62074 12.0075 6.65588 11.9226L8.50402 12.687ZM8.59971 11.8807C8.55686 11.6082 8.43972 11.3528 8.26117 11.1426L6.73683 12.4374C6.67731 12.3673 6.63827 12.2822 6.62399 12.1914L8.59971 11.8807ZM8.26225 11.1439L6.43125 8.9809L4.90475 10.2731L6.73575 12.4361L8.26225 11.1439ZM6.43105 8.98066C6.26886 8.78918 6.18458 8.54371 6.19496 8.29299L4.19667 8.21031C4.16555 8.9625 4.41837 9.69889 4.90495 10.2733L6.43105 8.98066ZM6.19496 8.29299C6.20533 8.04226 6.30959 7.80459 6.48705 7.62716L5.07295 6.21284C4.54058 6.74513 4.22779 7.45812 4.19667 8.21031L6.19496 8.29299ZM6.48711 7.62711L8.12711 5.98711L6.71289 4.57289L5.07289 6.21289L6.48711 7.62711ZM8.12743 5.98678C8.3048 5.80925 8.54243 5.7049 8.79316 5.69443L8.70974 3.69617C7.95755 3.72757 7.24466 4.04063 6.71257 4.57322L8.12743 5.98678ZM8.79316 5.69443C9.04389 5.68396 9.28939 5.76815 9.48093 5.93028L10.7731 4.40372C10.1984 3.91733 9.46193 3.66477 8.70974 3.69617L8.79316 5.69443ZM9.4811 5.93043L11.6441 7.76043L12.9359 6.23357L10.7729 4.40357L9.4811 5.93043ZM11.6426 7.75917C11.8528 7.93773 12.1082 8.05486 12.3807 8.09771L12.6914 6.12199C12.7822 6.13627 12.8673 6.17531 12.9374 6.23483L11.6426 7.75917ZM12.3807 8.09771C12.6531 8.14055 12.9321 8.10745 13.187 8.00202L12.4226 6.15388C12.5075 6.11874 12.6005 6.1077 12.6914 6.12199L12.3807 8.09771ZM13.187 8.00202C13.4419 7.8966 13.6628 7.72295 13.8254 7.50015L12.2098 6.32117C12.264 6.2469 12.3376 6.18902 12.4226 6.15388L13.187 8.00202ZM13.8254 7.50015C13.9879 7.27736 14.086 7.01405 14.1086 6.73917L12.1154 6.57483C12.1229 6.4832 12.1556 6.39543 12.2098 6.32117L13.8254 7.50015ZM14.1086 6.73993L14.3436 3.91593L12.3504 3.75007L12.1154 6.57407L14.1086 6.73993ZM12.347 3.833V3.834H14.347V3.833H12.347ZM22.5 16C22.5 14.4087 21.8679 12.8826 20.7426 11.7574L19.3284 13.1716C20.0786 13.9217 20.5 14.9391 20.5 16H22.5ZM20.7426 11.7574C19.6174 10.6321 18.0913 10 16.5 10V12C17.5609 12 18.5783 12.4214 19.3284 13.1716L20.7426 11.7574ZM16.5 10C14.9087 10 13.3826 10.6321 12.2574 11.7574L13.6716 13.1716C14.4217 12.4214 15.4391 12 16.5 12V10ZM12.2574 11.7574C11.1321 12.8826 10.5 14.4087 10.5 16H12.5C12.5 14.9391 12.9214 13.9217 13.6716 13.1716L12.2574 11.7574ZM10.5 16C10.5 17.5913 11.1321 19.1174 12.2574 20.2426L13.6716 18.8284C12.9214 18.0783 12.5 17.0609 12.5 16H10.5ZM12.2574 20.2426C13.3826 21.3679 14.9087 22 16.5 22V20C15.4391 20 14.4217 19.5786 13.6716 18.8284L12.2574 20.2426ZM16.5 22C18.0913 22 19.6174 21.3679 20.7426 20.2426L19.3284 18.8284C18.5783 19.5786 17.5609 20 16.5 20V22ZM20.7426 20.2426C21.8679 19.1174 22.5 17.5913 22.5 16H20.5C20.5 17.0609 20.0786 18.0783 19.3284 18.8284L20.7426 20.2426Z"
        fill="#E2E8F0"
        mask="url(#path-1-inside-1_818_2787)"
      />
      <path
        d="M24.5 16C24.5 18.1217 23.6571 20.1566 22.1569 21.6569C20.6566 23.1571 18.6217 24 16.5 24C14.3783 24 12.3434 23.1571 10.8431 21.6569C9.34285 20.1566 8.5 18.1217 8.5 16C8.5 13.8783 9.34285 11.8434 10.8431 10.3431C12.3434 8.84285 14.3783 8 16.5 8C18.6217 8 20.6566 8.84285 22.1569 10.3431C23.6571 11.8434 24.5 13.8783 24.5 16ZM21 16C21 14.8065 20.5259 13.6619 19.682 12.818C18.8381 11.9741 17.6935 11.5 16.5 11.5C15.3065 11.5 14.1619 11.9741 13.318 12.818C12.4741 13.6619 12 14.8065 12 16C12 17.1935 12.4741 18.3381 13.318 19.182C14.1619 20.0259 15.3065 20.5 16.5 20.5C17.6935 20.5 18.8381 20.0259 19.682 19.182C20.5259 18.3381 21 17.1935 21 16Z"
        fill="#64748B"
      />
      <path
        d="M11 16C11 14.5413 11.5795 13.1424 12.6109 12.1109C13.6424 11.0795 15.0413 10.5 16.5 10.5C17.9587 10.5 19.3576 11.0795 20.3891 12.1109C21.4205 13.1424 22 14.5413 22 16C22 17.4587 21.4205 18.8576 20.3891 19.8891C19.3576 20.9205 17.9587 21.5 16.5 21.5C15.0413 21.5 13.6424 20.9205 12.6109 19.8891C11.5795 18.8576 11 17.4587 11 16ZM21.5 16C21.5 14.6739 20.9732 13.4021 20.0355 12.4645C19.0979 11.5268 17.8261 11 16.5 11C15.1739 11 13.9021 11.5268 12.9645 12.4645C12.0268 13.4021 11.5 14.6739 11.5 16C11.5 17.3261 12.0268 18.5979 12.9645 19.5355C13.9021 20.4732 15.1739 21 16.5 21C17.8261 21 19.0979 20.4732 20.0355 19.5355C20.9732 18.5979 21.5 17.3261 21.5 16Z"
        fill="#E2E8F0"
      />
    </svg>
  );
}